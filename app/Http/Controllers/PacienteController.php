<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Exports\PacientesExport;
use Maatwebsite\Excel\Facades\Excel;

class PacienteController extends Controller
{
    /**
     * Muestra la lista completa de pacientes.
     */
    public function index(Request $request)
{
    $buscar = $request->get('search');

    $pacientes = Paciente::when($buscar, function ($query) use ($buscar) {
        return $query->where('nombre', 'LIKE', "%{$buscar}%")
                     ->orWhere('rut', 'LIKE', "%{$buscar}%");
    })
    ->latest()
    ->paginate(10)
    ->appends(['search' => $buscar]); // Mantiene la búsqueda al cambiar de página

    return view('pacientes.index', compact('pacientes', 'buscar'));
}

    /**
     * Muestra el formulario para registrar un nuevo paciente.
     */
    public function create()
    {
        return view('pacientes.create');
    }

    /**
     * Guarda un nuevo paciente en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
    'rut' => [
        'required',
        'string',
        // Expresión regular que exige: 1 a 2 dígitos + . + 3 dígitos + . + 3 dígitos + - + 1 dígito o K
        'regex:/^(\d{1,2}\.\d{3}\.\d{3}-[\dkK])$/',
        // Garantiza que sea único en la tabla pacientes (ajusta según la tabla)
        'unique:pacientes,rut,' . ($paciente->id ?? 'NULL'),
    ],
    'nombre' => 'required|string|max:255',
    'telefono' => 'required|string',
    'correo' => 'nullable|email',
], 
[
    'rut.regex' => 'El RUT debe tener un formato válido con puntos y guion (ej: 12.345.678-K).',
    'rut.unique' => 'Este RUT ya se encuentra registrado.',
]);

        Paciente::create($request->all());

        return redirect()->route('pacientes.index')
                         ->with('success', 'Paciente registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un paciente existente.
     */
    public function edit($id)
{
    $paciente = Paciente::findOrFail($id);
    return view('pacientes.edit', compact('paciente'));
}

    /**
     * Actualiza los datos del paciente en la base de datos.
     */
    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            'rut'      => 'required|string|max:20|unique:pacientes,rut,' . $paciente->id,
            'nombre'   => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'correo'   => 'required|email|max:255|unique:pacientes,correo,' . $paciente->id,
        ]);

        $paciente->update($request->all());

        return redirect()->route('pacientes.index')
                         ->with('success', 'Datos del paciente actualizados correctamente.');
    }

    /**
     * Elimina un paciente de la base de datos.
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')
                         ->with('success', 'Paciente eliminado correctamente.');
    }

   /**
 * API para autocompletado de pacientes por RUT o Nombre.
 */
    public function buscar(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (empty($query)) {
            return response()->json([]);
        }

        // Convertir la búsqueda a minúsculas y quitar puntos/espacios del RUT
        $queryMinuscula = mb_strtolower($query, 'UTF-8');
        $rutLimpio = str_replace(['.', ' '], '', $queryMinuscula);

        // Consulta insensible a mayúsculas/minúsculas (funciona en PostgreSQL, Supabase y MySQL)
        $pacientes = Paciente::whereRaw('LOWER(rut) LIKE ?', ["%{$rutLimpio}%"])
            ->orWhereRaw('LOWER(nombre) LIKE ?', ["%{$queryMinuscula}%"])
            ->limit(8)
            ->get(['id', 'rut', 'nombre']);

        return response()->json($pacientes);
    }

    public function exportarExcel()
{
    return Excel::download(new PacientesExport, 'pacientes_molaris_' . date('Y-m-d') . '.xlsx');
}

        public function show($id)
        {
            // Obtiene el paciente o retorna 404 si no existe
            $paciente = Paciente::findOrFail($id);

            return view('pacientes.ficha', compact('paciente'));
        }

        public function guardarAlertas(Request $request, $id)
        {
            $request->validate([
                'alergias' => 'nullable|string|max:500',
                'enfermedades_cronicas' => 'nullable|string|max:500',
            ]);

            $paciente = Paciente::findOrFail($id);
            $paciente->alergias = $request->input('alergias');
            $paciente->enfermedades_cronicas = $request->input('enfermedades_cronicas');
            $paciente->save();

            return redirect()->back()->with('success', 'Alertas de salud actualizadas con éxito.');
        }

   
}