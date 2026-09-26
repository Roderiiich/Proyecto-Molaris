<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function index()
    {
        $boxes = Box::orderBy('id', 'asc')->get();
        return view('boxes.index', compact('boxes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'estado' => 'required|in:Disponible,Mantenimiento,Inactivo',
        ]);

        Box::create($request->all());

        return redirect()->route('boxes.index')->with('success', 'Box de atención registrado.');
    }

    public function updateEstado(Request $request, Box $box)
    {
        $request->validate([
            'estado' => 'required|in:Disponible,Mantenimiento,Inactivo',
        ]);

        $box->update(['estado' => $request->estado]);

        return redirect()->route('boxes.index')->with('success', 'Estado del Box actualizado.');
    }
}