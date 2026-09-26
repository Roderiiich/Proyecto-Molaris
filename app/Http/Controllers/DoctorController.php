<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        // Trae los doctores junto con la información de su usuario asociado
        $doctores = Doctor::with('usuario')->orderBy('created_at', 'desc')->get();
        // Carga usuarios para poder vincularlos al crear un doctor
        $usuarios = User::all(); 

        return view('doctores.index', compact('doctores', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id'   => 'required|exists:users,id|unique:doctores,usuario_id',
            'rut'          => 'required|string|max:20|unique:doctores,rut',
            'especialidad' => 'required|string|max:100',
        ], [
            'usuario_id.unique' => 'Este usuario ya está registrado como odontólogo.',
            'rut.unique'        => 'El RUT ingresado ya pertenece a un odontólogo.',
        ]);

        Doctor::create($request->all());

        return redirect()->route('doctores.index')->with('success', 'Odontólogo registrado exitosamente.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctores.index')->with('success', 'Odontólogo eliminado del registro.');
    }
}