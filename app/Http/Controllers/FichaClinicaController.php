<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\FichaClinica;
use App\Models\FichaAdjunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf; // Instalar via: composer require barryvdh/laravel-dompdf

class FichaClinicaController extends Controller
{
    public function show(Paciente $paciente)
    {
        $paciente->load(['fichasClinicas.adjuntos']);
        return view('pacientes.ficha', compact('paciente'));
    }

    public function store(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'motivo_consulta' => 'required|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'archivos.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,dcm|max:10240'
        ]);

        $ficha = $paciente->fichasClinicas()->create([
            'motivo_consulta' => $validated['motivo_consulta'],
            'diagnostico' => $validated['diagnostico'] ?? null,
            'tratamiento' => $validated['tratamiento'] ?? null,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $file) {
                $path = $file->store('fichas_adjuntos', 'public');
                $ficha->adjuntos()->create([
                    'nombre_original' => $file->getClientOriginalName(),
                    'ruta_archivo' => $path,
                    'tipo_mime' => $file->getClientMimeType()
                ]);
            }
        }

        return back()->with('success', 'Atención médica registrada correctamente.');
    }

    public function update(Request $request, FichaClinica $ficha)
    {
        $validated = $request->validate([
            'motivo_consulta' => 'required|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $ficha->update($validated);

        return back()->with('success', 'Atención médica actualizada exitosamente.');
    }

    public function destroy(FichaClinica $ficha)
    {
        foreach ($ficha->adjuntos as $adjunto) {
            Storage::disk('public')->delete($adjunto->ruta_archivo);
        }
        $ficha->delete();

        return back()->with('success', 'Registro eliminado del historial.');
    }

    public function updateAlertas(Request $request, Paciente $paciente)
    {
        $paciente->update([
            'alergias' => $request->alergias,
            'enfermedades_cronicas' => $request->enfermedades_cronicas,
        ]);

        return back()->with('success', 'Alertas médicas actualizadas.');
    }

    public function updateOdontograma(Request $request, Paciente $paciente)
    {
        $paciente->update([
            'odontograma_state' => $request->odontograma_state
        ]);

        return response()->json(['status' => 'success']);
    }

    public function descargarReceta(FichaClinica $ficha)
    {
        $paciente = $ficha->paciente;
        $pdf = Pdf::loadView('pdf.receta', compact('ficha', 'paciente'));
        return $pdf->download("Receta_{{$paciente->rut}}_{{$ficha->created_at->format('Ymd')}}.pdf");
    }
}