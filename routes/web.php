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
use App\Http\Controllers\InventarioController; 
use App\Http\Controllers\CitaConfirmacionController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\LiquidacionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\Financiero\ComisionController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas / Confirmación por Enlace Signado
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

// Confirmación Pública de Citas por el Paciente (sin autenticación)
Route::get('/citas/{cita}/confirmar-paciente', [CitaConfirmacionController::class, 'show'])
    ->name('citas.confirmar.paciente')
    ->middleware('signed');

Route::post('/citas/{cita}/confirmar-paciente', [CitaConfirmacionController::class, 'responder'])
    ->name('citas.confirmar.paciente.post')
    ->middleware('signed');


/*
/*
|--------------------------------------------------------------------------
| Rutas Compartidas (Autenticados: Administrador y Dentista)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Dashboard con redirección inteligente según Rol
    Route::get('/dashboard', function () {
        if (auth()->user()->rol?->nombre === 'Dentista') {
            return redirect()->route('agenda.personal');
        }
        return app(DashboardController::class)->index();
    })->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Agenda General y Citas
    
    

    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
    Route::patch('/agenda/{id}/confirmar', [AgendaController::class, 'confirmar'])->name('agenda.confirmar');
    Route::patch('/agenda/{id}/rechazar', [AgendaController::class, 'rechazar'])->name('agenda.rechazar');

    // Búsqueda y Gestión de Pacientes
    Route::get('/api/pacientes/buscar', [PacienteController::class, 'buscar'])->name('pacientes.buscar');
    Route::get('/pacientes/exportar-excel', [PacienteController::class, 'exportarExcel'])->name('pacientes.exportar');
    Route::resource('pacientes', PacienteController::class);

    // Ficha Clínica y Atenciones
    Route::get('/pacientes/{paciente}/ficha', [FichaClinicaController::class, 'show'])->name('fichas.show');
    Route::get('/pacientes/{paciente}/ficha-alias', [FichaClinicaController::class, 'show'])->name('pacientes.ficha');
    Route::post('/pacientes/{paciente}/ficha', [FichaClinicaController::class, 'store'])->name('fichas.store');
    Route::put('/pacientes/{paciente}/alertas', [FichaClinicaController::class, 'updateAlertas'])->name('pacientes.alertas');
    Route::put('/pacientes/{paciente}/odontograma', [FichaClinicaController::class, 'updateOdontograma'])->name('pacientes.odontograma');
    Route::put('/fichas/{ficha}', [FichaClinicaController::class, 'update'])->name('fichas.update');
    Route::delete('/fichas/{ficha}', [FichaClinicaController::class, 'destroy'])->name('fichas.destroy');
    Route::get('/fichas/{ficha}/receta-pdf', [FichaClinicaController::class, 'descargarReceta'])->name('fichas.receta');

    // Odontograma
    Route::get('/pacientes/{id}/odontograma/estados', [OdontogramaController::class, 'obtenerEstados']);
    Route::post('/pacientes/{id}/odontograma/guardar', [OdontogramaController::class, 'guardarEstado']);

   // ------------------------------------------------------------------
    // Módulo de Presupuestos (Accesible para Dentista y Administrador)
    // ------------------------------------------------------------------
    
    // Rutas de creación (fijas)
    Route::get('/presupuestos/crear', [PresupuestoController::class, 'create'])->name('presupuestos.create');
    Route::get('/presupuesto/crear', [PresupuestoController::class, 'create'])->name('presupuesto.create');
    Route::get('/financiero/presupuestos/crear', [PresupuestoController::class, 'create'])->name('financiero.presupuestos.create');

    // Rutas de guardado (POST) - Con alias para financiero.presupuestos.store
    Route::post('/presupuestos', [PresupuestoController::class, 'store'])->name('presupuestos.store');
    Route::post('/financiero/presupuestos', [PresupuestoController::class, 'store'])->name('financiero.presupuestos.store');

    // Rutas de listado (INDEX)
    Route::get('/presupuestos', [PresupuestoController::class, 'index'])->name('presupuestos.index');
    Route::get('/presupuesto', [PresupuestoController::class, 'index'])->name('presupuesto.index');
    Route::get('/financiero/presupuestos', [PresupuestoController::class, 'index'])->name('financiero.presupuestos.index');

    // Rutas de detalle y cambio de estado
    Route::get('/presupuestos/{presupuesto}', [PresupuestoController::class, 'show'])->name('presupuestos.show');
    Route::get('/presupuesto/{presupuesto}', [PresupuestoController::class, 'show'])->name('presupuesto.show');
    Route::get('/financiero/presupuestos/{presupuesto}', [PresupuestoController::class, 'show'])->name('financiero.presupuestos.show');
    
    Route::post('/presupuestos/{presupuesto}/cambiar-estado', [PresupuestoController::class, 'cambiarEstado'])->name('presupuestos.cambiar-estado');
    Route::post('/financiero/presupuestos/{presupuesto}/cambiar-estado', [PresupuestoController::class, 'cambiarEstado'])->name('financiero.presupuestos.cambiar-estado');
});


/*
| Rutas Exclusivas para ADMINISTRADOR (Acceso Total a Molaris)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Administrador'])->group(function () {

    // Doctores
    Route::get('/doctores', [DoctorController::class, 'index'])->name('doctores.index');
    Route::post('/doctores', [DoctorController::class, 'store'])->name('doctores.store');
    Route::delete('/doctores/{doctor}', [DoctorController::class, 'destroy'])->name('doctores.destroy');

    // Boxes de Atención
    Route::get('/boxes', [BoxController::class, 'index'])->name('boxes.index');
    Route::post('/boxes', [BoxController::class, 'store'])->name('boxes.store');
    Route::patch('/boxes/{box}/estado', [BoxController::class, 'updateEstado'])->name('boxes.updateEstado');
    Route::delete('/boxes/{id}', [BoxController::class, 'destroy'])->name('boxes.destroy');

    // Inventario
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    Route::patch('/inventario/{id}/stock', [InventarioController::class, 'actualizarStock'])->name('inventario.stock');
    Route::delete('/inventario/{id}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
    Route::get('/inventario/exportar-excel', [InventarioController::class, 'exportExcel'])->name('inventario.exportExcel');

    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
});

    // ------------------------------------------------------------------
    // Módulo Financiero Exclusivo de Administración
    // ------------------------------------------------------------------
    Route::prefix('financiero')->name('financiero.')->group(function () {

        // Pagos y Caja
        Route::post('pagos', [PagoController::class, 'store'])->name('pagos.store');
        Route::get('cierre-caja', [PagoController::class, 'cierreCaja'])->name('pagos.cierre');
        Route::get('pagos/{pago}/comprobante', [PagoController::class, 'generarComprobante'])->name('pagos.comprobante');

        // Liquidaciones
        Route::post('liquidaciones/{liquidacion}/pagar', [LiquidacionController::class, 'marcarComoPagada'])->name('liquidaciones.pagar');
        Route::resource('liquidaciones', LiquidacionController::class)->except(['edit', 'update']);

        // Proveedores y Compras
        Route::resource('proveedores', ProveedorController::class);
        Route::resource('compras', CompraController::class)->except(['edit', 'update']);

        // Comisiones y Tasas
        Route::prefix('comisiones')->name('comisiones.')->group(function () {
            Route::get('/configuracion', [ComisionController::class, 'index'])->name('index');
            Route::post('/configuracion', [ComisionController::class, 'storeTasa'])->name('store');
            Route::put('/tasas/{id}', [ComisionController::class, 'updateTasa'])->name('tasas.update');
            Route::delete('/tasas/{id}', [ComisionController::class, 'destroyTasa'])->name('tasas.destroy');
            Route::get('/liquidaciones', [ComisionController::class, 'liquidaciones'])->name('liquidaciones.index');
            Route::post('/liquidaciones', [ComisionController::class, 'generarLiquidacion'])->name('liquidaciones.store');
            Route::patch('/liquidaciones/{id}/pagar', [ComisionController::class, 'pagarLiquidacion'])->name('liquidaciones.pagar');
            Route::get('/calcular-recaudacion', [ComisionController::class, 'calcularRecaudacion'])->name('calcular-recaudacion');
        });
    });




// 2. Rutas de la Agenda para Dentista
Route::middleware(['auth', 'role:Dentista'])->group(function () {
    Route::get('/agenda-general', [AgendaController::class, 'general'])->name('agenda.general');
    Route::get('/mi-agenda', [AgendaController::class, 'miAgenda'])->name('agenda.personal');
});

require __DIR__.'/auth.php';