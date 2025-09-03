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
    Route::get('/usuarios', function () {
        return view('usuarios/gestion_usuarios');
    })->name('gestionusuario')->middleware(CheckAdmin::class);
    Route::post('/usuarios', [UsuarioController::class, 'create'])->name('user.create');
    //clientes
    Route::get('/clientes', function () {
        return view('cliente/gestion_cliente');
    })->name('gestioncliente');
    Route::get('/registro-cliente', function () {
        return view('cliente/registro_cliente');
    })->name('registrocliente');
    Route::post('/registro-cliente', [ClienteController::class, 'store'])->name('cliente.store');
    //mascotas
    Route::get('/mascotas', function () {
        return view('mascota/gestion_mascota');
    })->name('gestionmascota');
    Route::get('/registro-mascotas', function () {
        return view('mascota/registro_mascota');
    })->name('registromascota');
    Route::post('/registro-mascotas', [MascotaController::class, 'store'])->name('mascota.store');
    //citas
    Route::get('/citas', function () {
        return view('cita/gestion_cita');
    })->name('gestioncita');
    Route::get('/agendar-cita', [CitaController::class, 'obtenerMascotas']);
    Route::post('/citas', [CitaController::class, 'agendar'])->name('cita.agendar');
    //historial citas
    Route::get('/historial-citas', function () {
        return view('cita/gestion_historial_cita');
    })->name('historialcita')->middleware(CheckSuper::class);
});
