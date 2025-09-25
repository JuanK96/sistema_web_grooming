<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\MascotaController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckSuper;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    //usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('gestionusuario')->middleware(CheckAdmin::class);
    Route::post('/usuarios/create', [UsuarioController::class, 'create'])->name('user.create');
    Route::get('/usuarios/search', [UsuarioController::class, 'search'])->name('user.search');
    //clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('gestioncliente');
    Route::get('/clientes/register', function () {
        return view('cliente/registro_cliente');
    })->name('registrocliente');
    Route::post('/clientes/register', [ClienteController::class, 'store'])->name('cliente.store');
    Route::get('/clientes/search', [ClienteController::class, 'search'])->name('cliente.search');
    Route::post('/clientes/update', [ClienteController::class, 'update'])->name('cliente.update');
    //mascotas
    Route::get('/mascotas', [MascotaController::class, 'index'])->name('gestionmascota');
    Route::get('/mascotas/register', function () {
        return view('mascota/registro_mascota');
    })->name('registromascota');
    Route::post('/mascotas/register', [MascotaController::class, 'store'])->name('mascota.store');
    Route::get('/mascotas/search', [MascotaController::class, 'search'])->name('mascota.search');
    Route::post('/mascotas/update', [MascotaController::class, 'update'])->name('mascota.update');
    //citas
    Route::get('/citas', [CitaController::class, 'index'])->name('gestioncita');
    Route::get('/citas/obtener-mascotas', [CitaController::class, 'obtenerMascotas']);
    Route::post('/citas/agendar', [CitaController::class, 'agendar'])->name('cita.agendar');
    Route::post('/citas/update', [CitaController::class, 'update'])->name('cita.update');
    //historial citas
    Route::get('/historial-citas', function () {
        return view('cita/gestion_historial_cita');
    })->name('historialcita')->middleware(CheckSuper::class);
});
