<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Muestra la vista de perfil del usuario.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    // 1. Validar si el archivo realmente está llegando desde el formulario
    if (!$request->hasFile('avatar')) {
        dd('ERROR: El servidor NO está recibiendo ningún archivo en $request->hasFile("avatar")');
    }

    $file = $request->file('avatar');

    // 2. Intentar la conversión a Base64
    $base64Image = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file));

    // 3. Forzar asignación directa y guardar
    $user->avatar = $base64Image;
    $user->name = $request->input('name', $user->name);
    $user->email = $request->input('email', $user->email);

    // Intentar guardar atrapando cualquier excepción de la base de datos
    try {
        $saved = $user->save();
        
        // 4. Diagnóstico final: Verificar qué se guardó realmente en memoria
        dd([
            'status' => 'Guardado exitoso en DB',
            'resultado_save' => $saved,
            'avatar_longitud_caracteres' => strlen($user->avatar),
            'avatar_inicio' => substr($user->avatar, 0, 50) . '...'
        ]);
    } catch (\Exception $e) {
        // Muestra el error exacto de PostgreSQL/Supabase
        dd([
            'status' => 'ERROR DE BASE DE DATOS AL GUARDAR',
            'mensaje_error' => $e->getMessage()
        ]);
    }
}

    /**
     * Elimina la cuenta del usuario junto con su foto de perfil.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Eliminar avatar antes de borrar la cuenta
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}