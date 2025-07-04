<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Empresa::query();
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

        // Si el request pide "all", devuelve todo como array
        if ($request->get('all') == 1) {
            return response()->json($query->get());
        }
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
            'cuit' => 'required|string',
            'activo' => 'nullable|boolean',
        ]);
        $empresa = Empresa::create($validated);
        return response()->json($empresa, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Empresa::findOrFail($id);
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
        $empresa = Empresa::findOrFail($id);
        $validated = $request->validate([
            'descripcion' => 'required|string',
            'cuit' => 'required|string',
            'activo' => 'nullable|boolean',
        ]);
        $empresa->update($validated);
        return response()->json($empresa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->activo = false;
        $empresa->save();
        return response()->json(['message' => 'Empresa desactivada']);
    }
}
