<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Redirigir raiz a la cartelera
Route::get('/', function () {
    return redirect()->route('cartelera');
});

// Rutas de Breeze
require __DIR__.'/auth.php';

// ==========================================
// Rutas públicas (sin autenticación)
// ==========================================

// Cartelera
Route::get('/cartelera', [ClienteController::class, 'cartelera'])
    ->name('cartelera');

// Funciones de una pelicula
Route::get('/pelicula/{id}/funciones', [ClienteController::class, 'funciones'])
    ->name('pelicula.funciones');

// Seleccionar cantidad de boletos
Route::get('/funcion/{id}/cantidad', [ClienteController::class, 'seleccionarCantidad'])
    ->name('funcion.cantidad');

// Seleccionar asientos
Route::get('/funcion/{id}/asientos/{cantidad}', [ClienteController::class, 'seleccionarAsientos'])
    ->name('funcion.asientos');

// Preparar compra (público: guarda selección en sesión y redirige a login si es necesario)
Route::post('/boleto/preparar', [ClienteController::class, 'prepararCompra'])
    ->name('boleto.preparar');

// ==========================================
// Rutas del cliente (requieren autenticación)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Resumen (lee datos de sesión, requiere login)
    Route::get('/boleto/resumen', [ClienteController::class, 'resumen'])
        ->name('boleto.resumen');

    // Preparar pago (recibe datos de resumen y guarda en sesión)
    Route::post('/boleto/pago/preparar', [ClienteController::class, 'prepararPago'])
        ->name('boleto.pago.preparar');

    // Selección de método de pago
    Route::get('/boleto/pago', [ClienteController::class, 'mostrarPago'])
        ->name('boleto.pago');

    // Confirmar compra
    Route::post('/boleto/confirmar', [ClienteController::class, 'confirmar'])
        ->name('boleto.confirmar');

    // Resultado
    Route::get('/boleto/resultado', [ClienteController::class, 'resultado'])
        ->name('boleto.resultado');

    // Mis boletos
    Route::get('/mis-boletos', [ClienteController::class, 'misBoletos'])
        ->name('mis.boletos');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// Rutas del Administrador
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // Peliculas CRUD
    Route::get('/peliculas', [AdminController::class, 'peliculas'])->name('admin.peliculas');
    Route::get('/peliculas/crear', [AdminController::class, 'peliculasCreate'])->name('admin.peliculas.create');
    Route::post('/peliculas', [AdminController::class, 'peliculasStore'])->name('admin.peliculas.store');
    Route::get('/peliculas/{id}/editar', [AdminController::class, 'peliculasEdit'])->name('admin.peliculas.edit');
    Route::put('/peliculas/{id}', [AdminController::class, 'peliculasUpdate'])->name('admin.peliculas.update');
    Route::delete('/peliculas/{id}', [AdminController::class, 'peliculasDestroy'])->name('admin.peliculas.destroy');

    // Salas CRUD
    Route::get('/salas', [AdminController::class, 'salas'])->name('admin.salas');
    Route::get('/salas/crear', [AdminController::class, 'salasCreate'])->name('admin.salas.create');
    Route::post('/salas', [AdminController::class, 'salasStore'])->name('admin.salas.store');
    Route::get('/salas/{id}/editar', [AdminController::class, 'salasEdit'])->name('admin.salas.edit');
    Route::put('/salas/{id}', [AdminController::class, 'salasUpdate'])->name('admin.salas.update');
    Route::delete('/salas/{id}', [AdminController::class, 'salasDestroy'])->name('admin.salas.destroy');

    // Funciones CRUD
    Route::get('/funciones', [AdminController::class, 'funciones'])->name('admin.funciones');
    Route::get('/funciones/crear', [AdminController::class, 'funcionesCreate'])->name('admin.funciones.create');
    Route::post('/funciones', [AdminController::class, 'funcionesStore'])->name('admin.funciones.store');
    Route::get('/funciones/{id}/editar', [AdminController::class, 'funcionesEdit'])->name('admin.funciones.edit');
    Route::put('/funciones/{id}', [AdminController::class, 'funcionesUpdate'])->name('admin.funciones.update');
    Route::delete('/funciones/{id}', [AdminController::class, 'funcionesDestroy'])->name('admin.funciones.destroy');

    // Boletos (read-only)
    Route::get('/boletos', [AdminController::class, 'boletos'])->name('admin.boletos');

    // Usuarios (read-only)
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
});