<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Rutas protegidas para Recepción y Administrador
Route::middleware(['auth', 'role:Recepción'])->group(function () {
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
    // Aquí irán las rutas para ver la vista diaria/semanal de la agenda
});

require __DIR__.'/auth.php';
