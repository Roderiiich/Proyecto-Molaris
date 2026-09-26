<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgendaController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\BoxController;

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
    Route::resource('pacientes', PacienteController::class);
    // Rutas para Odontólogos
    Route::get('/doctores', [DoctorController::class, 'index'])->name('doctores.index');
    Route::post('/doctores', [DoctorController::class, 'store'])->name('doctores.store');
    Route::delete('/doctores/{doctor}', [DoctorController::class, 'destroy'])->name('doctores.destroy');

    // Rutas para Boxes de Atención
    Route::get('/boxes', [BoxController::class, 'index'])->name('boxes.index');
    Route::post('/boxes', [BoxController::class, 'store'])->name('boxes.store');
    Route::patch('/boxes/{box}/estado', [BoxController::class, 'updateEstado'])->name('boxes.updateEstado');
});

require __DIR__.'/auth.php';