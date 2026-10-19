<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::withCount('compras')
            ->withSum('compras', 'monto_total')
            ->orderBy('nombre')
            ->get();

        return view('financiero.proveedores.index', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'     => 'required|string|max:255',
            'rut'        => 'nullable|string|max:255',
            'telefono'   => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'evaluacion' => 'required|integer|between:1,5',
            'notas'      => 'nullable|string',
        ]);

        Proveedor::create($validated);

        return redirect()->route('financiero.proveedores.index')
            ->with('success', 'Proveedor registrado exitosamente.');
    }

    public function update(Request $request, Proveedor $proveedore)
    {
        $validated = $request->validate([
            'nombre'     => 'required|string|max:255',
            'rut'        => 'nullable|string|max:255',
            'telefono'   => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'evaluacion' => 'required|integer|between:1,5',
            'notas'      => 'nullable|string',
        ]);

        $proveedore->update($validated);

        return redirect()->route('financiero.proveedores.index')
            ->with('success', 'Información del proveedor actualizada.');
    }

    public function destroy(Proveedor $proveedore)
    {
        $proveedore->delete();

        return redirect()->route('financiero.proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }
}