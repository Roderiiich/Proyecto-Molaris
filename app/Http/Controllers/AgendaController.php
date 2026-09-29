<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;

class AgendaController extends Controller
{
    public function index()
    {
        return view('agenda.index');
    }
    public function confirmar($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['estado' => 'confirmada']);

        return redirect()->back()->with('success', 'La cita fue confirmada con éxito.');
    }

    public function rechazar($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['estado' => 'rechazada']);

        return redirect()->back()->with('success', 'La cita fue rechazada.');
    }
}