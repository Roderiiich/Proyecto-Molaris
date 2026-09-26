<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Muestra la lista completa de pacientes.
     */
    public function index()
    {
        $pacientes = Paciente::orderBy('created_at', 'desc')->get();
        return view('pacientes.index', compact('pacientes'));
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
            'rut'      => 'required|string|max:20|unique:pacientes,rut',
            'nombre'   => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'correo'   => 'required|email|max:255|unique:pacientes,correo',
        ], [
            'rut.unique'      => 'El RUT ingresado ya está registrado.',
            'correo.unique'   => 'El correo electrónico ya está registrado.',
            'rut.required'    => 'El campo RUT es obligatorio.',
            'nombre.required' => 'El campo Nombre es obligatorio.',
        ]);

        Paciente::create($request->all());

        return redirect()->route('pacientes.index')
                         ->with('success', 'Paciente registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un paciente existente.
     */
    public function edit(Paciente $paciente)
    {
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
}