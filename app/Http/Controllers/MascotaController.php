<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MascotaService;

class MascotaController extends Controller
{
    protected $mascotaService;

    // INYECCIÓN DE DEPENDENCIAS POR CONSTRUCTOR
    public function __construct(MascotaService $mascotaService)
    {
        $this->mascotaService = $mascotaService;
    }

    public function index()
    {
        try {
            $mascotas = $this->mascotaService->listarConEdadCalculada(5);
            return view('mascota/gestion_mascota', compact('mascotas'));
        } catch (\Exception $e) {
            return redirect()->route('gestionmascota')->with('error', 'Error al cargar mascotas');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestionmascota')->with('error', 'Ingresa un término de búsqueda');
        }

        try {
            $mascotas = $this->mascotaService->buscarConEdadCalculada($search);
            if ($mascotas->isNotEmpty()) {
                return view('mascota/gestion_mascota', compact('mascotas'));
            } else {
                return redirect()->route('gestionmascota')->with('error', 'No se encontraron resultados');
            }
        } catch (\Exception $e) {
            return redirect()->route('gestionmascota')->with('error', 'Error en la busqueda');
        }
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'dueño' => 'required|string|exists:clientes,documento',
                'especies' => 'required|string|max:255',
                'raza' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'fechaNacimiento' => 'required|date|before:today',
                'weight' => 'required|numeric|max:255',
                'sex' => 'required|string|max:255',
                'sterilized' => 'nullable|string',
                'vaccinated' => 'nullable|string',
                'diseases' => 'nullable|string|max:255',
                'description' => 'required|string|max:255',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]
        );

        try {
            $this->mascotaService->registrarMascota($request->all(), $request->file('photo'));
            return redirect()->route('gestionmascota')->with('success', 'Mascota registrada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('gestionmascota')->with('error', 'Error al registrar la mascota');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'idmascota' => 'required|exists:mascotas,id',
            'date' => 'date|before:today',
            'weight' => 'numeric|max:255',
            'sterilized' => 'nullable|string',
            'vaccinated' => 'nullable|string',
            'diseases' => 'nullable|string|max:255',
            'description' => 'string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $this->mascotaService->actualizarMascota($request->idmascota, $request->all(), $request->file('photo'));
            return redirect()->route('gestionmascota')->with('success', 'Mascota actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('gestionmascota')->with('error', 'Error al actualizar la mascota');
        }
    }
}
