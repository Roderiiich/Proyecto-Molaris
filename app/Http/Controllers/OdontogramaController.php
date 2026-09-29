<?php

namespace App\Http\Controllers;

use App\Models\DienteEstado;
use App\Models\Paciente;
use Illuminate\Http\Request;

class OdontogramaController extends Controller
{
    // Obtener todos los dientes registrados del paciente
    public function obtenerEstados($pacienteId)
    {
        $estados = DienteEstado::where('paciente_id', $pacienteId)->get();
        return response()->json($estados);
    }

    // Guardar o actualizar la condición de una cara/diente
    public function guardarEstado(Request $request, $pacienteId)
    {
        $validated = $request->validate([
            'numero_diente' => 'required|integer',
            'cara'          => 'required|string',
            'estado'        => 'required|string',
            'observacion'   => 'nullable|string',
        ]);

        $diente = DienteEstado::updateOrCreate(
            [
                'paciente_id'   => $pacienteId,
                'numero_diente' => $validated['numero_diente'],
                'cara'          => $validated['cara'],
            ],
            [
                'estado'        => $validated['estado'],
                'observacion'   => $validated['observacion'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Estado guardado correctamente',
            'data'    => $diente
        ]);
    }
}