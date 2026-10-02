<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Rol; // Importante para la asignación del rol
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importante para DB::transaction

class DoctorController extends Controller
{
    public function index()
    {
        // Obtiene la lista de doctores junto a sus datos de la tabla 'users'
        $doctores = Doctor::with('usuario')->orderBy('created_at', 'desc')->get();

        // Filtra los usuarios de la tabla 'users' que aún NO son doctores
        $usuarios = User::whereNotIn('id', Doctor::pluck('usuario_id'))->get();

        return view('doctores.index', compact('doctores', 'usuarios'));
    }

    public function store(Request $request)
    {
        // 1. Validación con los nombres EXACTOS de los inputs de tu formulario Blade
        $request->validate([
            'usuario_id'   => 'required|exists:users,id|unique:doctores,usuario_id',
            'rut'          => 'required|string|unique:doctores,rut',
            'especialidad' => 'required|string|max:255',
        ], [
            'usuario_id.required'   => 'Debe seleccionar un usuario asociado.',
            'usuario_id.unique'     => 'Este usuario ya está dado de alta como odontólogo.',
            'rut.required'          => 'El RUT es obligatorio para el registro.',
            'rut.unique'            => 'Este RUT ya se encuentra registrado.',
            'especialidad.required' => 'Debe seleccionar una especialidad médica.',
        ]);

        DB::transaction(function () use ($request) {
            // 2. Obtener el ID del rol Dentista
            $rolDentista = Rol::where('nombre', 'Dentista')->first();

            // 3. Asignar el rol al usuario seleccionado (usando usuario_id)
            $user = User::findOrFail($request->usuario_id);
            
            if ($rolDentista) {
                $user->update([
                    'rol_id' => $rolDentista->id,
                ]);
            }

            // 4. Crear la ficha en la tabla doctores
            Doctor::updateOrCreate(
                ['usuario_id' => $user->id],
                [
                    'rut'          => $request->rut,
                    'especialidad' => $request->especialidad,
                ]
            );
        });

        return redirect()->back()
            ->with('success', 'Odontólogo dado de alta correctamente.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->back()->with('success', 'Odontólogo/a eliminado/a del registro.');
    }
}