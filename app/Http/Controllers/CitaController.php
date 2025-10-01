<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function obtenerMascotas(Request $request)
    {
        $request->validate([
            'dueño' => 'required|string',
        ]);
        try {
            $cliente = Cliente::where('documento', $request->input('dueño'))->first();
            if (!$cliente) {
                return response()->json([], 200);
            } else {
                $mascotas = Mascota::where('id_cliente', $cliente->id)->get();
                return response()->json($mascotas);
            }
        } catch (\Exception $e) {
            // Aquí puedes registrar el error o mostrar un mensaje personalizado
            return redirect()->route('gestioncita')->with('error', 'Error al obtener la mascotas');
        }
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestioncita')->with('error', 'Ingresa un término de búsqueda');
        } else {
            try {
                $usuarios = User::where('id_role', 3)->where('active', 1)->select('id', 'name')->get();
                $citas = Cita::with(['cliente:id,documento,name', 'mascota:id,name', 'usuario:id,name'])
                    ->where(function ($query) use ($search) {
                        //  Buscar por nombre de la mascota
                        $query->whereHas('mascota', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "{$search}%");
                        })
                            // Buscar por documento del cliente
                            ->orWhereHas('cliente', function ($query) use ($search) {
                                $query->where('documento', 'LIKE', "{$search}%");
                            })
                            ->orWhereHas('usuario', function ($query) use ($search) {
                                $query->where('name', 'LIKE', "{$search}%");
                            });
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(5)
                    ->appends(['search' => $search]);
                if ($citas->isNotEmpty()) {
                    return view('cita/gestion_cita', compact('citas', 'usuarios'));
                } else {
                    return redirect()->route('gestioncita')->with('error', 'No se encontraron resultados');
                }
            } catch (\Exception $e) {
                // Aquí puedes registrar el error o mostrar un mensaje personalizado
                return redirect()->route('gestioncita')->with('error', 'Error en la busqueda');
            }
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
                'grummer' => 'required',
            ]
        );
        try {
            Cita::create([
                'id_cliente' => Cliente::where('documento', $request->dueño)->value('id'),
                'id_mascota' => $request->mascota,
                'id_user' => $request->grummer,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
            ]);
            return redirect()->route('gestioncita');
        } catch (\Exception $e) {
            // Aquí puedes registrar el error o mostrar un mensaje personalizado
            return redirect()->route('gestioncita')->with('error', 'Error al registrar la cita');
        }
    }
    public function index()
    {
        try {
            $hoy = Carbon::today()->toDateString();
            $usuarios = User::where('id_role', 3)->where('active', 1)->select('id', 'name')->get();
            $citas = Auth::user()->citas()
                ->with(['cliente:id,documento,name', 'mascota:id,name', 'usuario:id,name'])
                ->whereDate('fecha', $hoy)
                ->orderBy('hora', 'asc')
                ->paginate(5);
            // $citas = Cita::with('cliente', 'mascota', 'usuario')->orderBy('hora', 'asc')->paginate(5);
            return view('cita/gestion_cita', compact('citas', 'usuarios'));
        } catch (\Exception $e) {
            // Aquí puedes registrar el error o mostrar un mensaje personalizado
            return redirect()->route('gestioncita')->with('error', 'Error al cargar las citas');
        }
    }
    public function update(Request $request)
    {
        // dd($request->all());
        // Validar datos recibidos
        $request->validate([
            'estado' => 'required|string',
            'description' => 'required|string',
        ]);
        try {
            $cita = Cita::findOrFail($request->idcita);
            if (Auth::id() == $cita->id_user) {
                if ($cita->created_at == $cita->updated_at) {
                    $cita->update([
                        'estado' => $request->estado,
                        'descripcion' => $request->description,
                    ]);
                    return redirect()->route('gestioncita')->with('success', 'Estado de cita Actualizado');
                } else {
                    return redirect()->route('gestioncita')->with('error', 'Cita Ya Actualizada');
                }
            } else {
                return redirect()->route('gestioncita')->with('error', 'Usuario no puede actualizar la cita');
            }
        } catch (\Exception $e) {
            // Aquí puedes registrar el error o mostrar un mensaje personalizado
            return redirect()->route('gestioncita')->with('error', 'Error al actualizar cita');
        }
    }
}
