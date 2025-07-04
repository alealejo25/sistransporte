<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = \App\Models\Service::with([
            'empresa', // <--- esto es clave
            'coche',
            'empleado',
            'user',
            'detalles.repuesto',
            'historiales.user',
            'historiales.empleado'
        ]);

        // Filtro por empresa
        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        // Filtro por coche
        if ($request->filled('coche_id')) {
            $query->where('coche_id', $request->coche_id);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_asignacion', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_asignacion', '<=', $request->fecha_hasta);
        }

        // Búsqueda general (por patente, interno, descripción, etc)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('coche', function($q) use ($search) {
                $q->where('patente', 'like', "%$search%")
                  ->orWhere('interno', 'like', "%$search%");
            })->orWhere('descripcion', 'like', "%$search%");
        }

        // Orden dinámico
        $sortField = $request->get('sortField', 'fecha_asignacion');
        $sortDirection = $request->get('sortDirection', 'desc');

        // Solo permitir campos válidos para evitar SQL Injection
        $allowedFields = [
            'empresa', 'coche', 'empleado', 'fecha_asignacion', 'estado', 'tipo'
        ];
        // Mapear campos a columnas reales si es necesario
        $fieldMap = [
            'empresa' => 'empresa_id',
            'coche' => 'coche_id',
            'empleado' => 'empleado_id',
            'fecha_asignacion' => 'fecha_asignacion',
            'estado' => 'estado',
            'tipo' => 'tipo',
        ];

        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($fieldMap[$sortField], $sortDirection);
        } else {
            $query->orderBy('fecha_asignacion', 'desc');
        }

        $perPage = $request->get('perPage', 15);

        return response()->json($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar y crear el service
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'coche_id' => 'required|exists:coches,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_asignacion' => 'required|date',
            'descripcion' => 'nullable|string',
            'km' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'detalles' => 'required|array|min:1',
            'detalles.*.repuesto_id' => 'required|exists:repuestos,id',
            'detalles.*.cantidad' => 'required|numeric|min:1',
        ]);

        // Chequear si ya existe un service pendiente o en proceso para el coche
        $existe = \App\Models\Service::where('coche_id', $request->coche_id)
            ->whereIn('estado', ['pendiente', 'en proceso'])
            ->exists();

        if ($existe) {
            return response()->json([
                'error' => 'Ya existe un service pendiente o en proceso para este coche.'
            ], 400);
        }

        \DB::beginTransaction();
        try {
            $service = \App\Models\Service::create([
                'empresa_id' => $validated['empresa_id'],
                'coche_id' => $validated['coche_id'],
                'empleado_id' => $validated['empleado_id'],
                'fecha_asignacion' => $validated['fecha_asignacion'],
                'descripcion' => $validated['descripcion'] ?? '',
                'estado' => 'pendiente', // <-- Forzado siempre a pendiente
                'km' => $validated['km'],
                'user_id' => $validated['user_id'],
            ]);

            // Registrar historial de creación
            \App\Models\ServiceHistorial::create([
                'service_id'       => $service->id,
                'empleado_id'      => $validated['empleado_id'],
                'estado_anterior'  => 'creado',
                'estado_nuevo'     => 'pendiente',
                'fecha_cambio'     => now(),
                'observacion'      => 'Creación de service',
                'user_id'          => $validated['user_id'],
            ]);

            $avisos = [];
            foreach ($validated['detalles'] as $detalle) {
                // Crear el detalle
                \App\Models\ServiceDetalle::create([
                    'service_id' => $service->id,
                    'repuesto_id' => $detalle['repuesto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'user_id' => $validated['user_id'], // <-- AGREGA ESTA LÍNEA
                ]);

                // Actualizar stock_global en repuesto
                $repuesto = \App\Models\Repuesto::find($detalle['repuesto_id']);
                if ($repuesto) {
                    if ($repuesto->stock_global < $detalle['cantidad']) {
                        \DB::rollBack();
                        return response()->json([
                            'error' => 'Stock global insuficiente para el repuesto: ' . $repuesto->descripcion
                        ], 400);
                    }
                    $repuesto->stock_global -= $detalle['cantidad'];
                    $repuesto->save();
                }

                // Actualizar cantidad en stock_repuesto de la empresa
                $stockRepuesto = \App\Models\StockRepuesto::where('empresa_id', $validated['empresa_id'])
                    ->where('repuesto_id', $detalle['repuesto_id'])
                    ->first();
                if ($stockRepuesto) {
                    if ($stockRepuesto->cantidad < $detalle['cantidad']) {
                        $avisos[] = "Stock insuficiente en la empresa para el repuesto: " . $repuesto->descripcion;
                    }
                    $stockRepuesto->cantidad -= $detalle['cantidad']; // Puede quedar negativo
                    $stockRepuesto->save();
                }
            }

            \DB::commit();
            return response()->json([
                'success' => true,
                'service' => $service,
                'avisos' => $avisos
            ], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al crear el service', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Cambiar el estado de un service
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,finalizado,cancelado,en proceso',
            'observacion' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $service = \App\Models\Service::findOrFail($id);
        $estadoAnterior = $service->estado;
        $service->estado = $request->estado;

        if (in_array($request->estado, ['finalizado', 'cancelado'])) {
            $service->observacion = $request->observacion;
            $service->fecha_terminacion = $request->fecha_terminacion;
        }

        $service->save();

        // Registrar en historial
        $service->historiales()->create([
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $request->estado,
            'user_id' => $request->user_id,
            'empleado_id' => $service->empleado_id, 
            'observacion' => $request->observacion,
            'service_id' => $service->id,
            'fecha_cambio' => now(), 
        ]);

        return response()->json(['estado' => $service->estado]);
    }

    /**
     * Actualizar repuestos de un service
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizarRepuestos(Request $request, $id)
    {
        $request->validate([
            'detalles' => 'required|array|min:1',
            'detalles.*.repuesto_id' => 'required|exists:repuestos,id',
            'detalles.*.cantidad' => 'required|numeric|min:1',
            'user_id' => 'required|exists:users,id',
        ]);

        $service = \App\Models\Service::with('detalles')->findOrFail($id);

        \DB::beginTransaction();
        try {
            // Revertir stock de los repuestos actuales
            foreach ($service->detalles as $detalle) {
                // Devolver stock a la empresa
                $stock = \App\Models\StockRepuesto::where('empresa_id', $service->empresa_id)
                    ->where('repuesto_id', $detalle->repuesto_id)
                    ->first();
                if ($stock) {
                    $stock->cantidad += $detalle->cantidad;
                    $stock->save();
                }
                // Devolver stock global
                $repuesto = \App\Models\Repuesto::find($detalle->repuesto_id);
                if ($repuesto) {
                    $repuesto->stock_global += $detalle->cantidad;
                    $repuesto->save();
                }
            }

            // Eliminar detalles actuales
            $service->detalles()->delete();

            // Agregar nuevos detalles y descontar stock
            foreach ($request->detalles as $detalle) {
                \App\Models\ServiceDetalle::create([
                    'service_id' => $service->id,
                    'repuesto_id' => $detalle['repuesto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'user_id' => $request->user_id,
                ]);

                // Descontar stock empresa
                $stock = \App\Models\StockRepuesto::where('empresa_id', $service->empresa_id)
                    ->where('repuesto_id', $detalle['repuesto_id'])
                    ->first();
                if ($stock) {
                    $stock->cantidad -= $detalle['cantidad'];
                    $stock->save();
                }
                // Descontar stock global
                $repuesto = \App\Models\Repuesto::find($detalle['repuesto_id']);
                if ($repuesto) {
                    $repuesto->stock_global -= $detalle['cantidad'];
                    $repuesto->save();
                }
            }

            // Registrar en historial
            \App\Models\ServiceHistorial::create([
                'service_id' => $service->id,
                'empleado_id' => $service->empleado_id,
                'estado_anterior' => $service->estado,
                'estado_nuevo' => $service->estado,
                'fecha_cambio' => now(),
                'observacion' => 'Edición de repuestos',
                'user_id' => $request->user_id,
            ]);

            \DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener KPIs de los services
     *
     * @return \Illuminate\Http\Response
     */
    public function kpis()
    {
        $total = \App\Models\Service::count();
        $pendientes = \App\Models\Service::where('estado', 'pendiente')->count();
        $enProceso = \App\Models\Service::where('estado', 'en proceso')->count(); 
        $finalizados = \App\Models\Service::where('estado', 'finalizado')->count();
        $cancelados = \App\Models\Service::where('estado', 'cancelado')->count();

        $porEstado = \App\Models\Service::selectRaw('estado, COUNT(*) as cantidad')
            ->groupBy('estado')
            ->get();

        return response()->json([
            'kpis' => [
                'total' => $total,
                'pendientes' => $pendientes,
                'enProceso' => $enProceso,
                'finalizados' => $finalizados,
                'cancelados' => $cancelados,
            ],
            'porEstado' => $porEstado,
        ]);
    }

    /**
     * Obtener historial de cambios de estado de un service
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function historial($id)
    {
        $service = \App\Models\Service::with(['historiales.user', 'historiales.empleado'])->findOrFail($id);

        
        $historial = $service->historiales->map(function($h) {
            return [
                'fecha_cambio'     => $h->fecha_cambio,
                'estado_anterior'  => $h->estado_anterior,
                'estado_nuevo'     => $h->estado_nuevo,
                'user'             => $h->user ? ['name' => $h->user->name] : null,
                'empleado'         => $h->empleado ? ['nombre' => $h->empleado->nombre] : null,
                'observacion'      => $h->observacion,
            ];
        });

        return response()->json($historial);
    }
}
