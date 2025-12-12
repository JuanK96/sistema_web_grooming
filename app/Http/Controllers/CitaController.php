<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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
                ->orderBy('hora_inicio', 'asc')
                ->paginate(5);
            // $citas = Cita::with('cliente', 'mascota', 'usuario')->orderBy('id', 'asc')->paginate(5);
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
                            })
                            ->orWhere('id', 'LIKE', "%{$search}%");
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
        $request->validate([
            'dueño' => 'required|string|max:255',
            'mascota' => 'required|string|max:255',
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => 'required',
            'hora_final' => 'required',
            'grummer' => 'required',
        ]);
        try {
            $horaInicio = Carbon::createFromFormat('g:i A', $request->hora_inicio)->format('H:i');
            $horaFinal  = Carbon::createFromFormat('g:i A', $request->hora_final)->format('H:i');
            //Buscar cliente por documento
            $cliente = Cliente::where('documento', $request->dueño)->first();
            if (!$cliente) {
                return redirect()->route('gestioncita')
                    ->with('error', 'El cliente no está registrado. Verifica la información.');
            }
            $mascota = Mascota::where('id', $request->mascota)->value('name');
            $groomerId = $request->grummer;
            $fecha = $request->fecha;
            // Validar si existe traslape
            $existeTraslape = Cita::where('id_user', $groomerId)
                ->where('fecha', $fecha)
                ->where(function ($query) use ($horaInicio, $horaFinal) {
                    $query->where(function ($q) use ($horaInicio, $horaFinal) {
                        $q->where('hora_inicio', '<', $horaFinal)
                            ->where('hora_final', '>', $horaInicio);
                    });
                })
                ->exists();
            if ($existeTraslape) {
                return redirect()->route('gestioncita')
                    ->with('error', 'El horario seleccionado ya está ocupado. Por favor elige otro.');
            }
            // Usamos transacción por seguridad si en el futuro hay más operaciones
            DB::beginTransaction();
            Cita::create([
                'id_cliente' => $cliente->id,
                'id_mascota' => $request->mascota,
                'id_user' => $groomerId,
                'fecha' => $fecha,
                'hora_inicio' => $horaInicio,
                'hora_final' => $horaFinal,
            ]);
            DB::commit();
            //Enviar confirmación por WhatsApp
            if (!empty($cliente->tel)) {
                $primerNombre = explode(' ', trim($cliente->name))[0];
                $telefono = $cliente->tel;
                $fecha = Carbon::parse($fecha)->format('d/m/Y');
                $horaInicio = Carbon::parse($horaInicio)->format('g:i A');
                $horaFinal = Carbon::parse($horaFinal)->format('g:i A');
                $mensaje = "Hola {$primerNombre}, tu cita para {$mascota} está confirmada para el {$fecha} de las {$horaInicio} a las {$horaFinal}.✂️🐶
‼️ TEN EN CUENTA ‼️
                
• Llegadas superiores a 15 minutos después de la hora acordada, serán consideradas como no presentación y no podremos atenderte 🚫⏱️
                
• En caso de no presentarte, habrá restricción para reagendar de inmediato, ya que es necesario organizar los turnos 📅
                
• El valor del servicio puede variar según el estado del pelo y el comportamiento de tu mascota 🐾✂️😊
                
• Una hora después del llamado, se empieza a cobrar guardería con un valor de $10.000 por hora 🩵🐕
                
😊 ¡Te esperamos! ❤️🐶🐾";
                $this->enviarConfirmacionWhatsApp($telefono, $mensaje);
            }
            return redirect()->route('gestioncita')
                ->with('success', 'Cita agendada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al agendar cita: ' . $e->getMessage());
            return redirect()->route('gestioncita')
                ->with('error', 'Ocurrió un error al registrar la cita');
        }
    }
    public function enviarConfirmacionWhatsApp($telefono, $mensaje)
    {
        $instance = env('ULTRAMSG_INSTANCE');
        $token = env('ULTRAMSG_TOKEN');
        $telefono = ltrim($telefono, '+');
        $url = "https://api.ultramsg.com/$instance/messages/chat";
        try {
            $response = Http::post($url, [
                'token' => $token,
                'to' => $telefono,
                'body' => $mensaje,
            ]);
            if ($response->successful()) {
                return true;
            } else {
                Log::error("Error Ultramsg: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error enviando WhatsApp Ultramsg: " . $e->getMessage());
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
            // Buscar cliente por id
            $cita = Cita::findOrFail($request->idcita);
            if (Auth::id() == $cita->id_user) {
                if ($cita->created_at == $cita->updated_at) {
                    DB::beginTransaction();
                    $cita->update([
                        'estado' => $request->estado,
                        'descripcion' => $request->description,
                    ]);
                    DB::commit();
                    return redirect()->route('gestioncita')->with('success', 'Estado de cita Actualizado');
                } else {
                    return redirect()->route('gestioncita')->with('error', 'Cita Ya Actualizada');
                }
            } else {
                return redirect()->route('gestioncita')->with('error', 'El usuario no puede actualizar la cita');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('gestioncita')->with('error', 'Error al actualizar cita');
        }
    }
    public function horasDisponibles(Request $request)
    {
        try {
            $fecha = $request->input('fecha');
            $groomer = $request->input('grummer');
            $inicioJornada = \Carbon\Carbon::createFromTimeString('08:30:00');
            $finJornada = \Carbon\Carbon::createFromTimeString('18:00:00');
            $intervalo = 30; // minutos
            $horasInicio = [];
            $horasFin = [];
            // Generar horas de inicio (8:30 a 17:00)
            $hora = $inicioJornada->copy();
            while ($hora < $finJornada) {
                $horasInicio[$hora->format('g:i A')] = $hora->format('H:i:s');
                $hora->addMinutes($intervalo);
            }
            // Generar horas de fin (9:00 a 18:00)
            $hora = $inicioJornada->copy()->addMinutes($intervalo);
            while ($hora <= $finJornada) {
                $horasFin[$hora->format('g:i A')] = $hora->format('H:i:s');
                $hora->addMinutes($intervalo);
            }
            $citas = Cita::where('fecha', $fecha)
                ->where('id_user', $groomer)
                ->where('estado', 'PENDIENTE')
                ->get(['hora_inicio', 'hora_final']);
            // Filtrar horas de inicio (descartar las que caen dentro de una cita existente)
            $horasInicioDisponibles = array_filter(array_keys($horasInicio), function ($horaTexto) use ($horasInicio, $citas) {
                $hora = \Carbon\Carbon::createFromFormat('H:i:s', $horasInicio[$horaTexto]);
                return !$citas->contains(function ($cita) use ($hora) {
                    return $hora->between(
                        \Carbon\Carbon::createFromFormat('H:i:s', $cita->hora_inicio),
                        \Carbon\Carbon::createFromFormat('H:i:s', $cita->hora_final)->subMinute()
                    );
                });
            });
            // Filtrar horas de fin (descartar las que caen dentro de una cita existente como hora de fin)
            $horasFinDisponibles = array_filter(array_keys($horasFin), function ($horaTexto) use ($horasFin, $citas) {
                $hora = \Carbon\Carbon::createFromFormat('H:i:s', $horasFin[$horaTexto]);
                return !$citas->contains(function ($cita) use ($hora) {
                    // No permitir terminar dentro de una cita existente
                    return $hora->gt(\Carbon\Carbon::createFromFormat('H:i:s', $cita->hora_inicio)) &&
                        $hora->lt(\Carbon\Carbon::createFromFormat('H:i:s', $cita->hora_final));
                });
            });
            return response()->json([
                'inicio' => array_values($horasInicioDisponibles),
                'fin' => array_values($horasFinDisponibles),
            ]);
        } catch (\Exception $e) {
            Log::error('Error en horasDisponibles: ' . $e->getMessage());
            return response()->json(['error' => 'Error al consultar horas disponibles'], 500);
        }
    }
}
