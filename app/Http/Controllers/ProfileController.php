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
    
    // Asignar los campos validados excepto el avatar
    $user->fill($request->safe()->except('avatar'));

    // Si el usuario subió una nueva foto
    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');
        
        // Convertir la imagen a Base64 con el MIME type correcto (data:image/png;base64,...)
        $base64Image = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file));
        
        // Guardar la cadena directamente en el campo avatar
        $user->avatar = $base64Image;
    }

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
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