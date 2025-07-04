<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::query();
        // Filtro por activo/inactivo
        if ($request->has('activo')) {
            $activo = $request->get('activo');
            if ($activo === '0' || $activo === 0) {
                $query->where('activo', false);
            } else {
                $query->where('activo', true);
            }
        }
        // Filtros avanzados
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }
        if ($request->filled('cuit')) {
            $query->where('cuit', 'like', "%{$request->cuit}%");
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', "%{$request->email}%");
        }
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('cuit', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('telefono', 'like', "%$search%")
                  ->orWhere('direccion', 'like', "%$search%");
            });
        }
        $sortBy = $request->get('sortBy', 'id');
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortBy, $sortDirection);
        $perPage = $request->get('perPage', 10);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'cuit' => 'required|string|unique:proveedores,cuit',
            'email' => 'nullable|email|unique:proveedores,email',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'activo' => 'boolean',
        ]);
        $proveedor = Proveedor::create($validated);
        return response()->json($proveedor, 201);
    }

    public function show($id)
    {
        return Proveedor::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'required|string',
            'cuit' => 'required|string|unique:proveedores,cuit,' . $proveedor->id,
            'email' => 'nullable|email|unique:proveedores,email,' . $proveedor->id,
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'activo' => 'boolean',
        ]);
        $proveedor->update($validated);
        return response()->json($proveedor);
    }

    public function desactivar($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->activo = false;
        $proveedor->save();
        return response()->json(['message' => 'Proveedor desactivado']);
    }
}
