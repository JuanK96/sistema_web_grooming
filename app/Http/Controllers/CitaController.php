<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;

class CitaController extends Controller
{   
    public function obtenerMascotas(Request $request)
{   
    $request->validate([
        'dueño' => 'required|string',
    ]);
    
    $cliente = Cliente::where('documento', $request->input('dueño'))->first();
    if(!$cliente){
        return response()->json([], 200);
    }else{
        $mascotas = Mascota::where('id_cliente', $cliente->id)->get();
        return response()->json($mascotas);
    }
    
}
    public function agendar(Request $request){
        // dd($request->all());
        $request->validate(
            [
                'dueño'=>'required|string|max:255',
                'mascota'=>'required|string|max:255',
                'fecha'=>'required',
                'hora'=>'required',
            ]
        );

        Cita::create([
            'id_cliente'=>Cliente::where('documento' , $request->dueño)->value('id'),
            'id_mascota'=>$request->mascota,
            'fecha'=>$request->fecha,
            'hora'=>$request->hora,
        ]);
        return redirect()->route('gestioncita');
    }
    public function index(){
        $citas = Cita::with('cliente', 'mascota', 'usuario')->orderBy('created_at', 'desc')->take(5)->get();
        return view('cita/gestion_cita', compact('citas'));
    }
}
