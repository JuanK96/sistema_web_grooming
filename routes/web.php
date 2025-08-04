<?php

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
    Route::get('/cliente', function () {
        return view('cliente/registro_cliente');
    })->name('registrocliente');
    Route::get('/mascota', function () {
        return view('mascota/registo_mascota');
    })->name('registromascota');

});
