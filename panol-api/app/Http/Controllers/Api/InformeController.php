<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockRepuesto;
use App\Models\Empresa;
use App\Models\Repuesto;
use App\Models\Marca;
use App\Models\ServiceDetalle;

class InformeController extends Controller
{
    public function stockRepuestos(Request $request)
    {
        $empresaId = $request->query('empresa_id');
        $marcaId = $request->query('marca_id');
        $repuestoId = $request->query('repuesto_id');

        $query = \App\Models\StockRepuesto::with(['empresa', 'repuesto.marca']);

        if ($empresaId) {
            $query->where('empresa_id', $empresaId);
        }
        if ($repuestoId) {
            $query->where('repuesto_id', $repuestoId);
        }
        if ($marcaId) {
            $query->whereHas('repuesto', function($q) use ($marcaId) {
                $q->where('marca_id', $marcaId);
            });
        }

        $stock = $query->get();

        $data = [];
        foreach ($stock as $item) {
            $data[] = [
                'empresa_id' => $item->empresa->id ?? null,
                'empresa' => $item->empresa->descripcion ?? '',
                'repuesto_id' => $item->repuesto->id ?? null,
                'repuesto' => $item->repuesto->descripcion ?? '',
                'marca_id' => $item->repuesto->marca->id ?? null,
                'marca' => $item->repuesto->marca->descripcion ?? '',
                'stock_empresa' => $item->cantidad,
                'stock_global' => $item->repuesto->stock_global ?? 0,
            ];
        }
        return response()->json($data);
    }

    public function serviciosRealizados(Request $request)
    {
        $query = ServiceDetalle::with([
            'service.coche',
            'repuesto',
            'user',
            'service.historiales.empleado'
        ]);

        // Filtros
        if ($request->filled('coche_id')) {
            $query->whereHas('service', function($q) use ($request) {
                $q->where('coche_id', $request->coche_id);
            });
        }
        if ($request->filled('tipo_servicio_id')) {
            $query->whereHas('service', function($q) use ($request) {
                $q->where('tipo', $request->tipo_servicio_id); // <-- O AJUSTA SEGÚN TU FRONTEND
            });
        }
        if ($request->filled('empleado_id')) {
            $query->whereHas('service.historiales', function($q) use ($request) {
                $q->where('empleado_id', $request->empleado_id);
            });
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_colocacion', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_colocacion', '<=', $request->fecha_hasta);
        }

        $detalles = $query->orderBy('fecha_colocacion', 'desc')->get();

        // Agrupar por servicio
        $agrupado = $detalles->groupBy('service_id')->map(function($items) {
            $servicio = $items->first()->service;
            return [
                'servicio_id' => $servicio->id,
                'empresa' => optional($servicio->empresa)->descripcion ?? '',
                'coche' => $servicio->coche
                    ? ($servicio->coche->patente . ' - ' . $servicio->coche->interno)
                    : ($servicio->coche->descripcion ?? ''),
                'estado' => $servicio->estado ?? '',
                'fecha' => $servicio->fecha_asignacion,
                'tipo_servicio' => $servicio->tipo ?? '',
                'empleado' => optional($servicio->empleado)->nombre ?? '',
                'detalles' => $items->map(function($d) {
                    return [
                        'repuesto' => optional($d->repuesto)->descripcion ?? '',
                        'cantidad' => $d->cantidad,
                        'observaciones' => $d->observaciones,
                    ];
                })->values(),
            ];
        })->values();

        return response()->json($agrupado);
    }
}
