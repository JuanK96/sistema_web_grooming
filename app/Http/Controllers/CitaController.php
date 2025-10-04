<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\User;
use App\Models\Cita;
use Carbon\Carbon;

class CitaController extends Controller
{
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
            $idCliente = Cliente::where('documento', $request->dueño)->value('id');
            $cita = Cita::create([
                'id_cliente' => $idCliente,
                'id_mascota' => $request->mascota,
                'id_user' => $request->grummer,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
            ]);
            // enviar mensaje
            $cliente = Cliente::find($idCliente)->value('id','name', 'tel');
            $mascota = Mascota::find($request->mascota)->value('id','name');
            $telefono = $cliente->tel;
            $mensaje = "Hola {$cliente->name}, tu cita para {$mascota}, ha sido agendada para el {$cita->fecha} a las {$cita->hora}. ¡Gracias!";
            $this->enviarConfirmacionWhatsApp($telefono, $mensaje);
            return redirect()->route('gestioncita')->with('success', 'Cita agendada correctamente');
        } catch (\Exception $e) {
            // Aquí puedes registrar el error o mostrar un mensaje personalizado
            return redirect()->route('gestioncita')->with('error', 'Error al registrar la cita');
        }
    }
    public function enviarConfirmacionWhatsApp($telefono, $mensaje)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilioNumber = env('TWILIO_WHATSAPP_NUMBER');
        $client = new Client($sid, $token);
        try {
            $client->messages->create(
                "whatsapp:" . $telefono,
                [
                    'from' => $twilioNumber,
                    'body' => $mensaje
                ]
            );
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando WhatsApp: " . $e->getMessage());
            return false;
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
