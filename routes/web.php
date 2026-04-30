<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

// Redirigir raiz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de Breeze
require __DIR__.'/auth.php';

// Rutas del cliente (requieren autenticacion)
Route::middleware(['auth'])->group(function () {

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

    // Resumen
    Route::post('/boleto/resumen', [ClienteController::class, 'resumen'])
        ->name('boleto.resumen');

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