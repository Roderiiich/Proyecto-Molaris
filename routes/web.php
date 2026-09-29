<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FichaClinicaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OdontogramaController;

// En routes/web.php

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Agenda & Citas
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
});

Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Endpoints específicos de Pacientes (Deben ir antes de Route::resource)
    Route::get('/api/pacientes/buscar', [PacienteController::class, 'buscar'])->name('pacientes.buscar');
    Route::get('/pacientes/exportar-excel', [PacienteController::class, 'exportarExcel'])->name('pacientes.exportar');

    // CRUD de Pacientes
    Route::resource('pacientes', PacienteController::class);

    // Ficha Clínica y Atenciones
    // Se agregan "fichas.show" y "pacientes.ficha" a la misma ruta para compatibilidad
    Route::get('/pacientes/{paciente}/ficha', [FichaClinicaController::class, 'show'])->name('fichas.show');
    Route::get('/pacientes/{paciente}/ficha', [FichaClinicaController::class, 'show'])->name('pacientes.ficha');

    Route::post('/pacientes/{paciente}/ficha', [FichaClinicaController::class, 'store'])->name('fichas.store');
    Route::put('/pacientes/{paciente}/alertas', [FichaClinicaController::class, 'updateAlertas'])->name('pacientes.alertas');
    Route::put('/pacientes/{paciente}/odontograma', [FichaClinicaController::class, 'updateOdontograma'])->name('pacientes.odontograma');

    // Edición, Eliminación y PDF Receta
    Route::put('/fichas/{ficha}', [FichaClinicaController::class, 'update'])->name('fichas.update');
    Route::delete('/fichas/{ficha}', [FichaClinicaController::class, 'destroy'])->name('fichas.destroy');
    Route::get('/fichas/{ficha}/receta-pdf', [FichaClinicaController::class, 'descargarReceta'])->name('fichas.receta');

    // Doctores
    Route::get('/doctores', [DoctorController::class, 'index'])->name('doctores.index');
    Route::post('/doctores', [DoctorController::class, 'store'])->name('doctores.store');
    Route::delete('/doctores/{doctor}', [DoctorController::class, 'destroy'])->name('doctores.destroy');

    // Boxes de Atención
    Route::get('/boxes', [BoxController::class, 'index'])->name('boxes.index');
    Route::post('/boxes', [BoxController::class, 'store'])->name('boxes.store');
    Route::patch('/boxes/{box}/estado', [BoxController::class, 'updateEstado'])->name('boxes.updateEstado');
    Route::delete('/boxes/{id}', [BoxController::class, 'destroy'])->name('boxes.destroy');

    // Rutas para confirmar y rechazar desde la agenda
    Route::patch('/agenda/{id}/confirmar', [AgendaController::class, 'confirmar'])->name('agenda.confirmar');
    Route::patch('/agenda/{id}/rechazar', [AgendaController::class, 'rechazar'])->name('agenda.rechazar');

    Route::get('/pacientes/{id}/odontograma/estados', [OdontogramaController::class, 'obtenerEstados']);
    Route::post('/pacientes/{id}/odontograma/guardar', [OdontogramaController::class, 'guardarEstado']);
});

require __DIR__.'/auth.php';