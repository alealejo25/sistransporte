<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockRepuesto;
use App\Models\MovimientoStockRepuesto;

class StockRepuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function ingreso(Request $request)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'repuesto_id' => 'required|exists:repuestos,id',
            'cantidad' => 'required|integer',
            'observacion' => 'nullable|string|max:255',
        ]);

        $stock = StockRepuesto::where('empresa_id', $validated['empresa_id'])
            ->where('repuesto_id', $validated['repuesto_id'])
            ->firstOrFail();

        $stock->cantidad += $validated['cantidad'];
        $stock->save();

        // Crear el movimiento de stock
        $movimiento = MovimientoStockRepuesto::create([
            'empresa_id'   => $request->empresa_id,
            'repuesto_id'  => $request->repuesto_id,
            'cantidad'     => $request->cantidad,
            'observacion'  => $request->observacion,
            'user_id'      => $request->user_id,
            'fecha'        => $request->fecha,
        ]);

        // Actualizar el stock_global del repuesto
        \DB::table('repuestos')
            ->where('id', $request->repuesto_id)
            ->increment('stock_global', $request->cantidad);

        return response()->json(['message' => 'Stock actualizado', 'stock' => $stock]);
    }

    public function stockPorEmpresa($empresaId)
    {
        $stock = \App\Models\StockRepuesto::with('repuesto')
            ->where('empresa_id', $empresaId)
            ->where('activo', true)
            ->get()
            ->map(function($item) {
                return [
                    'stock_id' => $item->id,
                    'repuesto_id' => $item->repuesto_id,
                    'codigo' => $item->repuesto->codigo,
                    'descripcion' => $item->repuesto->descripcion,
                    'cantidad' => $item->cantidad,
                ];
            });

        return response()->json($stock);
    }

    public function actualizarStock(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'repuesto_id' => 'required|exists:repuestos,id',
            'cantidad' => 'required|integer',
            'observacion' => 'nullable|string',
            'activo' => 'required|boolean',
        ]);

        // Obtener stock anterior
        $stock = \DB::table('stock_repuestos')
            ->where('empresa_id', $request->empresa_id)
            ->where('repuesto_id', $request->repuesto_id)
            ->first();

        $cantidadAnterior = $stock ? $stock->cantidad : 0;
        $diferencia = $request->cantidad - $cantidadAnterior;

        if ($stock) {
            \DB::table('stock_repuestos')
                ->where('empresa_id', $request->empresa_id)
                ->where('repuesto_id', $request->repuesto_id)
                ->update([
                    'cantidad' => $request->cantidad,
                    'activo' => $request->activo,
                    'updated_at' => now(),
                ]);
        } else {
            \DB::table('stock_repuestos')->insert([
                'empresa_id' => $request->empresa_id,
                'repuesto_id' => $request->repuesto_id,
                'cantidad' => $request->cantidad,
                'activo' => $request->activo,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Registrar movimiento
        \App\Models\MovimientoStockRepuesto::create([
            'empresa_id' => $request->empresa_id,
            'repuesto_id' => $request->repuesto_id,
            'cantidad' => $diferencia,
            'observacion' => $request->observacion,
            'user_id' => auth()->id(),
            'fecha' => now()->toDateString(),
        ]);

        // Actualizar stock_global en repuestos
        \DB::table('repuestos')
            ->where('id', $request->repuesto_id)
            ->increment('stock_global', $diferencia);

        return response()->json(['success' => true]);
    }

    public function resumenStock()
    {
        \Log::info('Entró a resumenStock');
        $repuestos = \App\Models\Repuesto::with('marca')
            ->with(['stockRepuestos' => function($q) {
                $q->with('empresa');
            }])
            ->get();

        $data = $repuestos->map(function($rep) {
            $stockGlobal = $rep->stockRepuestos->sum('cantidad');
            $montoTotal = $rep->stockRepuestos->map(function($s) use ($rep) {
                return $s->cantidad * $rep->valor;
            })->sum();
            return [
                'id' => $rep->id,
                'codigo' => $rep->codigo,
                'descripcion' => $rep->descripcion,
                'marca' => $rep->marca->descripcion ?? '',
                'unidad' => $rep->unidad_medida,
                'valor' => $rep->valor,
                'stock_global' => $stockGlobal,
                'monto_total' => $montoTotal,
                'por_empresa' => $rep->stockRepuestos->map(function($s) use ($rep) {
                    // Obtener cantidades de otras empresas para este repuesto
                    $otrasEmpresas = $rep->stockRepuestos
                        ->where('empresa_id', '!=', $s->empresa_id)
                        ->map(function($o) {
                            return [
                                'empresa' => $o->empresa->descripcion ?? '',
                                'stock' => $o->cantidad,
                            ];
                        })
                        ->values();

                    return [
                        'empresa' => $s->empresa->descripcion ?? '',
                        'stock' => $s->cantidad,
                        'monto' => $s->cantidad * $rep->valor,
                        'stock_global' => $rep->stock_global,
                        'otras_empresas' => $otrasEmpresas,
                    ];
                })
            ];
        });

        return response()->json($data);
    }

    public function stockNegativo(Request $request)
    {
        $empresaId = $request->query('empresa_id');
        $negativos = \App\Models\StockRepuesto::with('repuesto')
            ->where('empresa_id', $empresaId)
            ->where('cantidad', '<', 0)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->repuesto_id,
                    'descripcion' => $item->repuesto->descripcion,
                    'cantidad' => $item->cantidad,
                ];
            });

        return response()->json($negativos);
    }

    public function stockNegativoTodas()
    {
        $negativos = \App\Models\StockRepuesto::with(['repuesto', 'empresa'])
            ->where('cantidad', '<', 0)
            ->get()
            ->groupBy('empresa_id')
            ->map(function($items, $empresaId) {
                return [
                    'empresa_id' => $empresaId,
                    'empresa' => $items->first()->empresa->descripcion ?? '',
                    'repuestos' => $items->map(function($item) {
                        // Stock global de ese repuesto (sumando todas las empresas)
                        $stockGlobal = \App\Models\StockRepuesto::where('repuesto_id', $item->repuesto_id)->sum('cantidad');
                        // Stock en otras empresas
                        $otrasEmpresas = \App\Models\StockRepuesto::with('empresa')
                            ->where('repuesto_id', $item->repuesto_id)
                            ->where('empresa_id', '!=', $item->empresa_id)
                            ->get()
                            ->map(function($o) {
                                return [
                                    'empresa' => $o->empresa->descripcion ?? '',
                                    'stock' => $o->cantidad,
                                ];
                            })
                            ->values();

                        return [
                            'id' => $item->repuesto_id,
                            'descripcion' => $item->repuesto->descripcion,
                            'cantidad' => $item->cantidad,
                            'stock_global' => $stockGlobal,
                            'otras_empresas' => $otrasEmpresas,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json($negativos);
    }
}
