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
    public function search(Request $request)
{
    $query = $request->input('search');

    $usuarios = User::where('id_role', 3)->where('active', 1)->get();

    $citas = Cita::with(['cliente', 'mascota', 'usuario'])
        ->where(function ($q) use ($query) {
            //  Buscar por nombre de la mascota
            $q->whereHas('mascota', function ($q2) use ($query) {
                $q2->where('name', 'LIKE', '%' . $query . '%');
            })
            // Buscar por documento del cliente
            ->orWhereHas('cliente', function ($q3) use ($query) {
                $q3->where('documento', 'LIKE', '%' . $query . '%');
            });
        })

        ->orderBy('created_at', 'desc')
        ->paginate(5);

    return view('cita/gestion_cita', compact('citas', 'usuarios'));
}


    public function agendar(Request $request)

    {
        // dd($request->all());
        $request->validate(
            [
                'dueño' => 'required|string|max:255',
                'mascota' => 'required|string|max:255',
                'fecha' => ['required', 'date', 'after_or_equal:today'],
                'hora_inicio'  => ['required', 'date_format:H:i', 'after_or_equal:08:00', 'before_or_equal:17:00'],
                'hora_final'  => ['required', 'date_format:H:i', 'after_or_equal:08:00', 'before_or_equal:17:00'],
                'grummer'=>'required',
            ]
        );
        Cita::create([
            'id_cliente' => Cliente::where('documento', $request->dueño)->value('id'),
            'id_mascota' => $request->mascota,
            'id_user'=>$request->grummer,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_final' => $request->hora_final,
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
        if(Auth::id()==$cita->id_user){
            if($cita->created_at==$cita->updated_at){
                $cita->update([
                'estado' => $request->estado,
                'descripcion' => $request->description, ]);
                return redirect()->route('gestioncita')->with('success', 'Estado de cita Actualizado');
            } else{
                return redirect()->route('gestioncita')->with('error', 'Cita Ya Actualizada');
            }
        }else{
            return redirect()->route('gestioncita')->with('error', 'Usuario no asiganado a la cita');
        }
    }
    public function horasDisponibles(Request $request)
{
    $fecha = $request->input('fecha');

    $horas = [];
    // Hora de inicio 8:30 am
    $inicio = \Carbon\Carbon::createFromTime(8, 30);
    // Hora final 6:00 pm
    $fin = \Carbon\Carbon::createFromTime(18, 0);

    // Generar intervalo cada 30 minutos
    while ($inicio <= $fin) {
        $horas[] = $inicio->format('h:i A');
        $inicio->addMinutes(30);
    }

    // Consultar horas ya ocupadas
    // $ocupadas = Cita::where('fecha', $fecha)->pluck('hora')->toArray();

    // // Filtrar quitando las horas ocupadas
    // $disponibles = array_filter($horas, function ($hora) use ($ocupadas) {
    //     $hora24 = \Carbon\Carbon::parse($hora)->format('H:i:s');
    //     return !in_array($hora24, $ocupadas);
    // });

    return response()->json(array_values($horas));
}
}
