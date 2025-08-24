<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
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
    })->name('gestionusuario');
    Route::post('/usuarios' , [UsuarioController::class , 'create'])->name('user.create');
    //clientes
    Route::get('/clientes', function () {
        return view('cliente/gestion_cliente');
    })->name('gestioncliente');
    Route::get('/registro-cliente', function () {
        return view('cliente/registro_cliente');
    })->name('registrocliente');
    Route::post('/registro-cliente' , [ClienteController::class , 'store'])->name('cliente.store');
    //mascotas
    Route::get('/mascotas', function () {
        return view('mascota/gestion_mascota');
    })->name('gestionmascota');
    Route::get('/registro-mascotas', function () {
        return view('mascota/registro_mascota');
    })->name('registromascota');
    //citas
    Route::get('/citas', function () {
        return view('cita/gestion_cita');
    })->name('gestioncita');
    Route::get('/historial-citas', function () {
        return view('cita/gestion_historial_cita');
    })->name('historialcita');
});
