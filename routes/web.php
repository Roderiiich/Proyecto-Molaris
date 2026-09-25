<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgendaController; // <-- Agrega esta línea arriba
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- AGREGA ESTA LÍNEA PARA TU AGENDA ---
Route::get('/agenda', [AgendaController::class, 'index'])->middleware(['auth', 'verified'])->name('agenda.index');
// Agrega esto debajo de Route::get('/agenda', ...)
Route::post('/citas', [CitaController::class, 'store'])->middleware(['auth', 'verified'])->name('citas.store');
// ----------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';