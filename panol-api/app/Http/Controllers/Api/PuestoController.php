<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Puesto;

class PuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Puesto::query();

        // Filtro por activo/inactivo
        if ($request->has('activo')) {
            $activo = $request->get('activo');
            if ($activo === '0' || $activo === 0) {
                $query->where('activo', false);
            } else {
                $query->where('activo', true);
            }
        }

        // Búsqueda
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
            'descripcion' => 'required|string',
            'activo' => 'boolean'
        ]);

        $puesto = Puesto::create($validated);
        return response()->json($puesto, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Puesto::findOrFail($id);
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
        $puesto = Puesto::findOrFail($id);

        $validated = $request->validate([
            'descripcion' => 'required|string',
            'activo' => 'boolean'
        ]);

        $puesto->update($validated);
        return response()->json($puesto);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $puesto = Puesto::findOrFail($id);
        $puesto->activo = false;
        $puesto->save();

        return response()->json(['message' => 'Puesto desactivado']);
    }
}
