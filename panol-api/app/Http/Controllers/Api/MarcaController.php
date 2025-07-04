<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Marca::query();

        // Filtro por activo/inactivo
        if ($request->has('activo')) {
            $activo = $request->get('activo');
            $query->where('activo', $activo ? 1 : 0);
        }

        // Búsqueda por descripción
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('descripcion', 'like', "%$search%");
        }

        // Ordenamiento
        $sortBy = $request->get('sortBy', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        // Paginado
        $perPage = $request->get('perPage', 10);
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
        $validated = $request->validate([
            'descripcion' => 'required|string|unique:marcas,descripcion',
            'activo' => 'boolean',
        ]);

        $marca = Marca::create($validated);

        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Marca::findOrFail($id);
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
        $marca = Marca::findOrFail($id);

        $validated = $request->validate([
            'descripcion' => 'required|string|unique:marcas,descripcion,' . $marca->id,
            'activo' => 'boolean',
        ]);

        $marca->update($validated);

        return response()->json($marca);
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

    public function desactivar($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->activo = false;
        $marca->save();

        return response()->json(['message' => 'Marca desactivada']);
    }
}
