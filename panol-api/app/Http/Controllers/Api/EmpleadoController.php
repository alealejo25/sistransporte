<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\Puesto;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $query = Empleado::with(['empresa', 'puesto']);

    // Filtro por activo/inactivo
    if ($request->has('activo')) {
        $activo = $request->get('activo');
        if ($activo === '0' || $activo === 0) {
            $query->where('activo', false);
        } else {
            $query->where('activo', true);
        }
    }

    // Filtro de búsqueda (por nombre, apellido, dni o email)
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%$search%")
              ->orWhere('apellido', 'like', "%$search%")
              ->orWhere('dni', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    // Ordenamiento
    $sortBy = $request->get('sortBy', 'id');
    $sortDirection = $request->get('sortDirection', 'asc');
    $query->orderBy($sortBy, $sortDirection);

    // Si el request pide "all", devuelve todo como array
    if ($request->get('all') == 1) {
        return response()->json($query->get());
    }

    // Paginado por defecto
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
        'nombre' => 'required|string',
        'apellido' => 'required|string',
        'dni' => 'required|string|unique:empleados,dni',
        'legajo' => 'nullable|string',
        'email' => 'nullable|email',
        'telefono' => 'nullable|string',
        'empresa_id' => 'required|exists:empresas,id',
        'puesto_id' => 'required|exists:puestos,id',
        'activo' => 'boolean',
        'foto' => 'nullable|image|max:2048', // solo imágenes, máx 2MB
    ]);


        if ($request->hasFile('foto')) {
        $validated['foto'] = $request->file('foto')->store('empleados', 'public');
    }
    \Log::info('Recibido archivo:', ['foto' => $request->file('foto')]);
    $empleado = Empleado::create($validated);

    return response()->json($empleado, 201);
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         return Empleado::with('empresa', 'puesto', 'user')->findOrFail($id);
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
    $empleado = Empleado::findOrFail($id);

    $validated = $request->validate([
        'nombre' => 'required|string',
        'apellido' => 'required|string',
        'dni' => 'required|string|unique:empleados,dni,' . $empleado->id,
        'legajo' => 'nullable|string',
        'email' => 'nullable|email',
        'telefono' => 'nullable|string',
        'empresa_id' => 'required|exists:empresas,id',
        'puesto_id' => 'required|exists:puestos,id',
        'activo' => 'boolean',
        'foto' => 'nullable|image|max:2048',
    ]);

    // Eliminar imagen anterior si suben una nueva
    if ($request->hasFile('foto')) {
        if ($empleado->foto) {
            \Storage::disk('public')->delete($empleado->foto);
        }

        $validated['foto'] = $request->file('foto')->store('empleados', 'public');
    }

    $empleado->update($validated);

    return response()->json($empleado);
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->activo = false;
        $empleado->save();

        return response()->json(['message' => 'Empleado desactivado']);
    }
}
