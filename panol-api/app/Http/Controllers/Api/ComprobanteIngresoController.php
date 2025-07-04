<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComprobanteIngreso;
use App\Models\ComprobanteIngresoDetalle;
use App\Models\Repuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComprobanteIngresoController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('Llega al método store');

        // Si viene como string (por FormData), decodificalo
        if ($request->has('detalle') && is_string($request->detalle)) {
            $request->merge([
                'detalle' => json_decode($request->detalle, true)
            ]);
        }

        try {
            $validated = $request->validate([
                'tipo_comprobante_id' => 'required|exists:tipo_comprobante,id',
                'numero' => 'required',
                'fecha' => 'required|date',
                'turno_panol_id' => 'required|exists:turnos_panol,id',
                'proveedor_id' => 'required|exists:proveedores,id',
                'empresa_id' => 'required|exists:empresas,id',
                'detalle' => 'required|array|min:1',
                'detalle.*.repuesto_id' => 'required|exists:repuestos,id',
                'detalle.*.cantidad' => 'required|numeric|min:1',
                'detalle.*.costo_unitario' => 'required|numeric|min:0',
                'detalle.*.observacion' => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Errores de validación:', $e->errors());
            throw $e;
        }
        Log::info('Después de la validación', ['request' => $request->all()]);

        DB::beginTransaction();
        try {
            // Guardar archivo si viene
            $archivoPath = null;
            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                $archivoPath = $archivo->store('comprobantes', 'public');
            }

            Log::info('Antes de crear comprobante');
            $comprobante = ComprobanteIngreso::create([
                'tipo_comprobante_id' => $request->tipo_comprobante_id,
                'numero' => $request->numero,
                'fecha' => $request->fecha,
                'turno_panol_id' => $request->turno_panol_id,
                'proveedor_id' => $request->proveedor_id,
                'empresa_id' => $request->empresa_id,
                'observaciones' => $request->observaciones,
                'usuario_id' => 1, // Toma el usuario autenticado
                'archivo' => $archivoPath,
            ]);
            Log::info('Comprobante creado', ['id' => $comprobante->id]);

            foreach ($request->detalle as $item) {
                Log::info('Procesando detalle', $item);

                ComprobanteIngresoDetalle::create([
                    'comprobante_ingreso_id' => $comprobante->id,
                    'repuesto_id' => $item['repuesto_id'],
                    'cantidad' => $item['cantidad'],
                    'costo_unitario' => $item['costo_unitario'],
                    'observacion' => $item['observacion'] ?? null,
                ]);
                Log::info('Detalle creado');

                $updated = DB::table('stock_repuestos')
                    ->where('empresa_id', $request->empresa_id)
                    ->where('repuesto_id', $item['repuesto_id'])
                    ->increment('cantidad', $item['cantidad']);
                Log::info('Stock repuestos actualizado', ['updated' => $updated]);

                $updatedGlobal = Repuesto::where('id', $item['repuesto_id'])
                    ->increment('stock_global', $item['cantidad']);
                Log::info('Stock global actualizado', ['updatedGlobal' => $updatedGlobal]);

                // Actualizar el valor del repuesto con el costo unitario ingresado
                if (isset($item['repuesto_id']) && isset($item['costo_unitario'])) {
                    \DB::table('repuestos')
                        ->where('id', $item['repuesto_id'])
                        ->update(['valor' => $item['costo_unitario']]);
                }
            }

            DB::commit();
            Log::info('Transacción completada');

            return response()->json([
                'message' => 'Comprobante guardado correctamente',
                'comprobante' => $comprobante->load('detalles')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar comprobante', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al guardar', 'error' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $query = ComprobanteIngreso::with([
            'empresa',
            'proveedor',
            'tipo_comprobante',
            'detalles.repuesto' 
        ]);

        // Búsqueda general
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero', 'like', "%$search%")
                  ->orWhereHas('proveedor', function($q2) use ($search) {
                      $q2->where('nombre', 'like', "%$search%");
                  })
                  ->orWhereHas('empresa', function($q3) use ($search) {
                      $q3->where('descripcion', 'like', "%$search%");
                  });
            });
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        // Filtro por empresa
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        // Filtro por proveedor
        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }

        // Orden y paginación
        $sortable = ['id', 'numero', 'fecha', 'proveedor_id', 'empresa_id'];
        $sortBy = $request->get('sortBy', 'id');
        $sortDirection = $request->get('sortDirection', 'desc');
        if (!in_array($sortBy, $sortable)) $sortBy = 'id';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'desc';

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($request->get('perPage', 10));
    }

    public function anular($id)
    {
        DB::beginTransaction();
        try {
            $comprobante = ComprobanteIngreso::with('detalles')->findOrFail($id);

            if ($comprobante->anulado) {
                return response()->json(['message' => 'El comprobante ya está anulado'], 400);
            }

            // Restaurar stock
            foreach ($comprobante->detalles as $detalle) {
                // Resta la cantidad al stock de la empresa
                DB::table('stock_repuestos')
                    ->where('empresa_id', $comprobante->empresa_id)
                    ->where('repuesto_id', $detalle->repuesto_id)
                    ->decrement('cantidad', $detalle->cantidad);

                // Resta la cantidad al stock global
                DB::table('repuestos')
                    ->where('id', $detalle->repuesto_id)
                    ->decrement('stock_global', $detalle->cantidad);
            }

            // Marcar como anulado
            $comprobante->anulado = 1;
            $comprobante->usuario_anulacion_id = auth()->id() ?? 1; // o el usuario actual
            $comprobante->fecha_anulacion = now();
            $comprobante->save();

            DB::commit();
            return response()->json(['message' => 'Comprobante anulado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al anular comprobante', 'error' => $e->getMessage()], 500);
        }
    }
}
