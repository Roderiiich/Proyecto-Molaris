<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuarioRoleController extends Controller
{
    /**
     * Muestra la lista de usuarios con sus roles actuales.
     */
    public function index()
    {
        $usuarios = User::with('rol')->orderBy('name', 'asc')->get();
        $roles = Rol::all();

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    /**
     * Actualiza el rol asignado a un usuario.
     */
    public function updateRol(Request $request, User $usuario)
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,id',
        ]);

        $usuario->update([
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->back()->with('success', "Rol actualizado correctamente para {$usuario->name}");
    }
}