<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coche;
use Illuminate\Http\Request;

class CocheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');
        $activo = $request->input('activo', 1);

        $query = Coche::with(['carroceria', 'modelo', 'marcaCoche', 'empresa']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('patente', 'like', "%$search%")
                  ->orWhere('interno', 'like', "%$search%")
                  ->orWhere('nroempresa', 'like', "%$search%");
            });
        }

        if ($activo !== null) {
            $query->where('activo', $activo);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $data = $request->validate([
            'interno' => 'required|integer',
            'nroempresa' => 'required|integer',
            'patente' => 'required|string|max:10',
            'anio' => 'required|integer',
            'motor' => 'required|string|max:30',
            'chasis' => 'required|string|max:30',
            'nroasientos' => 'required|integer',
            'valor' => 'required|integer',
            // ...otros campos opcionales...
        ]);
        $coche = Coche::create($data + $request->only([
            'empresa_id', 'marca_coche_id', 'modelo_id', 'carroceria_id', 'activo', 'km', 'fechavtv', 'vencimientovtv', 'ultimoservice', 'fecha_ingreso'
        ]));
        return response()->json($coche, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Coche::with(['carroceria', 'modelo', 'marcaCoche', 'empresa'])->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $coche = Coche::findOrFail($id);
        $data = $request->validate([
            'interno' => 'required|integer',
            'nroempresa' => 'required|integer',
            'patente' => 'required|string|max:10',
            'anio' => 'required|integer',
            'motor' => 'required|string|max:30',
            'chasis' => 'required|string|max:30',
            'nroasientos' => 'required|integer',
            'valor' => 'required|integer',
            'empresa_id' => 'nullable|integer|exists:empresas,id',
            'marca_coche_id' => 'nullable|integer|exists:marcas_coches,id',
            'modelo_id' => 'nullable|integer|exists:modelos,id',
            'carroceria_id' => 'nullable|integer|exists:carrocerias,id',
            'activo' => 'nullable|boolean',
            'km' => 'nullable|integer',
            'fechavtv' => 'nullable|date',
            'vencimientovtv' => 'nullable|date',
            'ultimoservice' => 'nullable|date',
            'fecha_ingreso' => 'nullable|date',
        ]);
        $coche->update($data);
        return response()->json($coche);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coche = Coche::findOrFail($id);
        $coche->activo = 0;
        $coche->save();
        return response()->json(['message' => 'Coche desactivado correctamente']);
    }    

    /**
     * Desactivar the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $coche = Coche::findOrFail($id);
        $coche->activo = 0;
        $coche->save();
        return response()->json(['message' => 'Coche desactivado correctamente']);
    }

    /**
     * Display a listing of the resource by company.
     *
     * @param  int  $empresaId
     * @return \Illuminate\Http\Response
     */
    public function porEmpresa($empresaId, Request $request)
    {
        $query = Coche::where('empresa_id', $empresaId)->where('activo', 1);

        if ($request->get('all') == 1) {
            return response()->json($query->get());
        }

        return response()->json($query->paginate(10));
    }
}
