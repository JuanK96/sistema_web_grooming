<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('role')->where('id', '>', 1)->orderBy('created_at', 'desc')->paginate(5);
        return view('usuarios/gestion_usuarios', compact('usuarios'));
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestionusuario')->with('error', 'Ingresa un término de búsqueda');
        } else {
            // Buscar clientes
            $usuarios = User::where('id', '!=', 1)
                ->where(function ($query) use ($search) {
                    $query->where('email', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                })
                ->paginate(5)
                ->appends(['search' => $search]);
            if ($usuarios->isNotEmpty()) {
                return view('usuarios/gestion_usuarios', compact('usuarios'));
            } else {
                return redirect()->route('gestionusuario')->with('error', 'No se encontraron resultados');
            }
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
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $request->role,
        ]);
        return redirect()->route('gestionusuario');
    }
    public function toggleActive(User $usuario)
    {
        $usuario->active = !$usuario->active; // alterna entre 1 y 0
        $usuario->save();
        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }
}
