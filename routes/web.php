<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\HistorialController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckSuper;
use App\Http\Middleware\CheckActive;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use LaravelLang\Config\Data\Shared\RoutesGroup;

Route::get('/', function () {
    return view('auth/login');
});

Route::middleware([
    'auth:sanctum',
    CheckActive::class,
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    //usuarios
    Route::middleware([CheckAdmin::class])->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('gestionusuario');
        Route::post('/usuarios/create', [UsuarioController::class, 'create'])->name('user.create');
        Route::get('/usuarios/search', [UsuarioController::class, 'search'])->name('user.search');
        Route::patch('/usuarios/{usuario}/toggle', [UsuarioController::class, 'toggleActive'])->name('usuarios.toggle');
    }); 
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
    Route::get('/citas/search', [CitaController::class, 'search'])->name('cita.search');
    Route::get('/citas/horas-disponibles', [CitaController::class, 'horasDisponibles']);
    //historial citas
    Route::middleware([CheckSuper::class])->group(function () {
        Route::get('/historial', [HistorialController::class, 'index'])->name('gestionhistorial');
        Route::get('/historial/search', [HistorialController::class, 'search'])->name('historial.search');
        Route::get('/historial/reporte', [HistorialController::class, 'reporte'])->name('historial.reporte');
    });
});
