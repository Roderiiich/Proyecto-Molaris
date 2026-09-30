<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\FichaClinica;
use App\Models\FichaAdjunto;
use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class FichaClinicaController extends Controller
{
    public function show(Paciente $paciente)
    {
        $paciente->load(['fichasClinicas.adjuntos', 'fichasClinicas.articulos']);
        
        // Obtenemos los insumos con stock disponible para el selector de Alpine.js
        $articulos = Articulo::where('stock_actual', '>', 0)->get();

        return view('pacientes.ficha', compact('paciente', 'articulos'));
    }

    public function store(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'motivo_consulta'   => 'required|string',
            'diagnostico'       => 'nullable|string',
            'tratamiento'       => 'nullable|string',
            'observaciones'     => 'nullable|string',
            'archivos.*'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,dcm|max:10240',
            'adjuntos.*'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,dcm|max:10240', // Compatible con name="adjuntos[]" o "archivos[]"
            'insumos'           => 'nullable|array',
            'insumos.*.id'      => 'required|exists:articulos,id',
            'insumos.*.cantidad'=> 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $paciente, $validated) {
            $ficha = $paciente->fichasClinicas()->create([
                'motivo_consulta' => $validated['motivo_consulta'],
                'diagnostico'     => $validated['diagnostico'] ?? null,
                'tratamiento'     => $validated['tratamiento'] ?? null,
                'observaciones'   => $validated['observaciones'] ?? null,
            ]);

            // Manejo de adjuntos (soporta 'archivos' o 'adjuntos')
            $files = $request->file('archivos') ?? $request->file('adjuntos');
            if ($files) {
                foreach ($files as $file) {
                    $path = $file->store('fichas_adjuntos', 'public');
                    $ficha->adjuntos()->create([
                        'nombre_original' => $file->getClientOriginalName(),
                        'ruta_archivo'    => $path,
                        'tipo_mime'       => $file->getClientMimeType()
                    ]);
                }
            }

            // Descuento e inserción de insumos/materiales
            if ($request->has('insumos') && is_array($request->insumos)) {
                foreach ($request->insumos as $insumo) {
                    $articulo = Articulo::findOrFail($insumo['id']);
                    
                    // Descontar stock
                    $articulo->decrement('stock_actual', $insumo['cantidad']);

                    if (method_exists($articulo, 'actualizarEstado')) {
                        $articulo->actualizarEstado();
                    }

                    // Vincular a la ficha clínica
                    $ficha->articulos()->attach($articulo->id, [
                        'cantidad' => $insumo['cantidad']
                    ]);
                }
            }
        });

        return back()->with('success', 'Atención médica registrada e insumos descontados correctamente.');
    }

    public function update(Request $request, FichaClinica $ficha)
    {
        $validated = $request->validate([
            'motivo_consulta' => 'required|string',
            'diagnostico'     => 'nullable|string',
            'tratamiento'     => 'nullable|string',
            'observaciones'   => 'nullable|string',
        ]);

        $ficha->update($validated);

        return back()->with('success', 'Atención médica actualizada exitosamente.');
    }

    public function destroy(FichaClinica $ficha)
    {
        DB::transaction(function () use ($ficha) {
            // Revertir el stock de insumos antes de eliminar
            foreach ($ficha->articulos as $articulo) {
                $cantidadDevuelta = $articulo->pivot->cantidad;
                $articulo->increment('stock_actual', $cantidadDevuelta);

                if (method_exists($articulo, 'actualizarEstado')) {
                    $articulo->actualizarEstado();
                }
            }

            // Eliminar archivos adjuntos
            foreach ($ficha->adjuntos as $adjunto) {
                Storage::disk('public')->delete($adjunto->ruta_archivo);
            }

            $ficha->delete();
        });

        return back()->with('success', 'Registro eliminado del historial y stock de insumos reincorporado.');
    }

    public function updateAlertas(Request $request, Paciente $paciente)
    {
        $paciente->update([
            'alergias'              => $request->alergias,
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
        return $pdf->download("Receta_{$paciente->rut}_{$ficha->created_at->format('Ymd')}.pdf");
    }
}