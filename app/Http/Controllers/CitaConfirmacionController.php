<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;

class CitaConfirmacionController extends Controller
{
    public function show(Cita $cita)
    {
        return view('citas.confirmar_paciente', compact('cita'));
    }

    public function responder(Request $request, Cita $cita)
    {
        $request->validate([
            'accion' => 'required|in:confirmar,rechazar',
        ]);

        if ($request->accion === 'confirmar') {
            $cita->update(['estado' => 'confirmada']);
            $mensaje = '¡Gracias! Tu cita ha sido confirmada con éxito.';
        } else {
            $cita->update(['estado' => 'rechazada']);
            $mensaje = 'Has rechazado la cita.';
        }

        return back()->with([
            'status' => $mensaje,
            'estado_actual' => $cita->estado
        ]);
    }
}