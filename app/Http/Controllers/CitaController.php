<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function obtenerMascotas(Request $request)
    {
        $request->validate([
            'dueño' => 'required|string',
        ]);

        $cliente = Cliente::where('documento', $request->input('dueño'))->first();
        if (!$cliente) {
            return response()->json([], 200);
        } else {
            $mascotas = Mascota::where('id_cliente', $cliente->id)->get();
            return response()->json($mascotas);
        }
    }
    public function agendar(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'dueño' => 'required|string|max:255',
                'mascota' => 'required|string|max:255',
                'fecha' => 'required',
                'hora' => 'required',
                'grummer'=>'required',
            ]
        );
        Cita::create([
            'id_cliente' => Cliente::where('documento', $request->dueño)->value('id'),
            'id_mascota' => $request->mascota,
            'id_user'=>$request->grummer,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
        ]);
        return redirect()->route('gestioncita');
    }
    public function index()
    {
        $usuarios = User::where('id_role',3 )->where('active',1)->get();
        //$citas = Auth::user()->citas()
        //->with(['cliente', 'mascota', 'usuario'])
       // ->orderBy('created_at', 'desc')
        //->paginate(5);
        $citas = Cita::with('cliente', 'mascota', 'usuario')->orderBy('created_at', 'desc')->paginate(5);
        return view('cita/gestion_cita', compact('citas','usuarios'));
    }
    public function update(Request $request)
    {
        // dd($request->all());
        // Validar datos recibidos
        $request->validate([
            'estado' => 'required|string',
            'description' => 'required|string',
        ]);
        // Buscar cliente por id
        $cita = Cita::findOrFail($request->idcita);
        // Actualizar campos
        $cita->update([
            'estado' => $request->estado,
            'descripcion' => $request->descrition,
            
        ]);
        return redirect()->route('gestioncita')->with('success', 'Estado de cita Actualizado');
    }
}
