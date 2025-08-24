<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UsuarioController extends Controller
{

    public function create(Request $request){
        
        $request->validate(
            [
                'name'=>'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'email'=>'required|string|email|max:255|unique:users,email',
                'password'=>'required|string|max:255|min:8|confirmed',
                'role'=>'required|string',
            ],
            [
                'name.required'=>'Por favor ingrese un nombre.',
                'email.unique'=>'El Usuario se encuentra registrado.',
                'password.required'=>'Por favor ingrese una contraseña',
                'password.min'=>'Por favor ingrese una contraseña de 8 o más caracteres',
                'password.confirmed'=>'Las contraseñas no coinciden',
                'role.required'=>'Debe asignar un rol',
            ]
        );
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'id_role'=>$request->role,
        ]);
        return redirect()->route('gestionusuario');
    }
}
