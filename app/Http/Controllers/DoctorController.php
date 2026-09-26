<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User; // Importamos el modelo User de Breeze
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        // Obtiene la lista de doctores junto a sus datos de la tabla 'users'
        $doctores = Doctor::with('usuario')->orderBy('created_at', 'desc')->get();

        // Filtra los usuarios de la tabla 'users' que aún NO son doctores (aquí aparecerá Catalina)
        $usuarios = User::whereNotIn('id', Doctor::pluck('usuario_id'))->get();

        return view('doctores.index', compact('doctores', 'usuarios'));
    }

    public function store(Request $request)
    {
        // Validamos contra la tabla 'users' de la base de datos
        $request->validate([
            'usuario_id'   => 'required|exists:users,id|unique:doctores,usuario_id',
            'rut'          => 'required|string|max:20|unique:doctores,rut',
            'especialidad' => 'required|string|max:100',
        ], [
            'usuario_id.exists' => 'El usuario seleccionado no existe en el sistema.',
            'usuario_id.unique' => 'Este usuario ya está asignado como odontólogo.',
            'rut.unique'        => 'El RUT ingresado ya pertenece a un odontólogo.',
        ]);

        Doctor::create([
            'usuario_id'   => $request->usuario_id,
            'rut'          => $request->rut,
            'especialidad' => $request->especialidad,
        ]);

        return redirect()->route('doctores.index')->with('success', 'Odontólogo/a asignado/a exitosamente.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctores.index')->with('success', 'Odontólogo/a eliminado/a del registro.');
    }
}