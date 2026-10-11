<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UsuarioService;

class UsuarioController extends Controller
{
    protected $usuarioService;

    // INYECCIÓN DE DEPENDENCIAS POR CONSTRUCTOR
    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    public function index()
    {
        try {
            $usuarios = $this->usuarioService->listarUsuarios(5);
            return view('usuarios/gestion_usuarios', compact('usuarios'));
        } catch (\Exception $e) {
            return redirect()->route('gestionusuario')->with('error', 'Error al cargar usuarios');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestionusuario')->with('error', 'Ingresa un término de búsqueda');
        }

        try {
            $usuarios = $this->usuarioService->buscarUsuarios($search, 5);
            if ($usuarios->isNotEmpty()) {
                return view('usuarios/gestion_usuarios', compact('usuarios'));
            } else {
                return redirect()->route('gestionusuario')->with('error', 'No se encontraron resultados');
            }
        } catch (\Exception $e) {
            return redirect()->route('gestionusuario')->with('error', 'Error en la busqueda');
        }
    }

    public function create(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|max:255|min:8|confirmed',
                'role' => 'required|string',
            ],
            [
                'name.required' => 'Por favor ingrese un nombre.',
                'email.unique' => 'El Usuario se encuentra registrado.',
                'password.required' => 'Por favor ingrese una contraseña',
                'password.min' => 'Por favor ingrese una contraseña de 8 o más caracteres',
                'password.confirmed' => 'Las contraseñas no coinciden',
                'role.required' => 'Debe asignar un rol',
            ]
        );

        try {
            $this->usuarioService->registrarUsuario($request->all());
            return redirect()->route('gestionusuario')->with('success', 'Usuario creado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('gestionusuario')->with('error', 'Error al registrar el usuario');
        }
    }

    public function toggleActive(User $usuario)
    {
        try {
            $this->usuarioService->cambiarEstado($usuario);
            return redirect()->back()->with('success', 'Estado actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('gestionusuario')->with('error', 'Error al actualizar estado');
        }
    }
}