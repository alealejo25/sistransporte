<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repuesto;
use App\Models\Empresa;
use App\Models\StockRepuesto;

class RepuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Repuesto::with('marca');

        // Filtro por activo/inactivo
        if ($request->has('activo')) {
            $activo = $request->get('activo');
            if ($activo === '0' || $activo === 0) {
                $query->where('activo', false);
            } else {
                $query->where('activo', true);
            }
        }

        // Filtro de búsqueda (por código o descripción)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'like', "%$search%")
                  ->orWhere('descripcion', 'like', "%$search%");
            });
        }

        // Ordenamiento
        $sortBy = $request->get('sortBy', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        // Si se solicita todos los registros sin paginación
        if ($request->get('all') == 1) {
            return response()->json($query->get());
        }

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
            'codigo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'marca_id' => 'required|integer|exists:marcas,id',
            'tipo' => 'required|string|max:50',
            'unidad_medida' => 'required|string|max:50',
            'activo' => 'required|boolean',
            'valor' => 'required|numeric|min:0',
        ]);

        $validated['stock_global'] = 0; // Fuerza siempre a 0

        $repuesto = Repuesto::create($validated);

        
        if ($repuesto->tipo !== 'cubiertas') {
            $empresas = Empresa::all();
            foreach ($empresas as $empresa) {
                StockRepuesto::create([
                    'empresa_id' => $empresa->id,
                    'repuesto_id' => $repuesto->id,
                    'cantidad' => 0,
                    'estado' => 'nuevo',
                    'fecha_actualiza' => now(),
                    'user_id' => auth()->id() ?? 1,
                    'activo' => true,
                ]);
            }
        }

        return response()->json($repuesto, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Repuesto::findOrFail($id);
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
        $validated = $request->validate([
            'codigo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'marca_id' => 'required|integer|exists:marcas,id',
            'tipo' => 'required|string|max:50',
            'unidad_medida' => 'required|string|max:50',
            'activo' => 'required|boolean',
            'valor' => 'required|numeric|min:0',
        ]);

        $validated['stock_global'] = 0; // Fuerza siempre a 0

        $repuesto = Repuesto::findOrFail($id);
        $repuesto->update($validated);
        return response()->json($repuesto);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $repuesto = Repuesto::findOrFail($id);
        $repuesto->delete();
        return response()->json(['message' => 'Repuesto eliminado']);
    }

    public function desactivar($id)
    {
        $repuesto = Repuesto::findOrFail($id);
        $repuesto->activo = 0;
        $repuesto->save();
        return response()->json(['message' => 'Repuesto desactivado']);
    }
}
