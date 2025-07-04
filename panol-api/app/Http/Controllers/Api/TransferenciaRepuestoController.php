<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransferenciaRepuesto;
use App\Models\StockRepuesto;
use Illuminate\Support\Facades\DB;

class TransferenciaRepuestoController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'repuesto_id' => 'required|exists:repuestos,id',
                'empresa_origen_id' => 'required|exists:empresas,id',
                'empresa_destino_id' => 'required|exists:empresas,id|different:empresa_origen_id',
                'cantidad' => 'required|integer|min:1',
                'user_id' => 'required|exists:users,id',
            ]);

            \DB::beginTransaction();

            $stockOrigen = \App\Models\StockRepuesto::where('empresa_id', $validated['empresa_origen_id'])
                ->where('repuesto_id', $validated['repuesto_id'])
                ->first();

            if (!$stockOrigen) {
                return response()->json(['error' => 'No existe stock en la empresa origen para este repuesto.'], 400);
            }

            if ($stockOrigen->cantidad < $validated['cantidad']) {
                return response()->json(['error' => 'Stock insuficiente en la empresa origen.'], 400);
            }

            $stockOrigen->cantidad -= $validated['cantidad'];
            $stockOrigen->save();

            $stockDestino = \App\Models\StockRepuesto::firstOrCreate(
                [
                    'empresa_id' => $validated['empresa_destino_id'],
                    'repuesto_id' => $validated['repuesto_id'],
                ],
                ['cantidad' => 0]
            );
            $stockDestino->cantidad += $validated['cantidad'];
            $stockDestino->save();

            $transferencia = \App\Models\TransferenciaRepuesto::create($validated);

            \DB::commit();
            return response()->json(['success' => true, 'transferencia' => $transferencia]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }

    public function index(Request $request)
    {
        $repuestos = StockRepuesto::with(['repuesto', 'empresa'])
            ->where('cantidad', '<', 0)
            ->get()
            ->groupBy('empresa_id')
            ->map(function ($items, $empresaId) {
                return [
                    'empresa_id' => $empresaId,
                    'empresa' => $items->first()->empresa->descripcion ?? '',
                    'repuestos' => $items->map(function ($item) {
                        $stockGlobal = StockRepuesto::where('repuesto_id', $item->repuesto_id)->sum('cantidad');
                        $otrasEmpresas = StockRepuesto::with('empresa')
                            ->where('repuesto_id', $item->repuesto_id)
                            ->where('empresa_id', '!=', $item->empresa_id)
                            ->get()
                            ->map(function ($o) {
                                return [
                                    'empresa' => $o->empresa->descripcion ?? '',
                                    'stock' => $o->cantidad,
                                ];
                            })
                            ->values();

                        return [
                            'id' => $item->repuesto_id,
                            'codigo' => $item->repuesto->codigo, 
                            'descripcion' => $item->repuesto->descripcion,
                            'cantidad' => $item->cantidad,
                            'stock_global' => $stockGlobal,
                            'otras_empresas' => $otrasEmpresas,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json($repuestos);
    }
}
