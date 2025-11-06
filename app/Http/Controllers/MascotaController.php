<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Mascota;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MascotaController extends Controller
{
    public function index()
    {
        try {
            $mascotas = Mascota::with('dueño:id,documento')->orderBy('id', 'desc')->paginate(5);
            foreach ($mascotas as $mascota) {
                $fechaNacimiento = Carbon::parse($mascota->fecha_nacimiento);
                $ahora = Carbon::now();
                $diasTotales = $fechaNacimiento->diffInDays($ahora);
                if ($diasTotales < 7) {
                    // Menos de una semana: mostrar días
                    $mascota->edad = "{$diasTotales} días";
                } elseif ($diasTotales < 365) {
                    // Menos de un año: mostrar semanas redondeadas
                    $semanas = round($diasTotales / 7);
                    $mascota->edad = "{$semanas} semanas";
                } else {
                    // Un año o más: mostrar años enteros y semanas restantes
                    $añosEnteros = floor($diasTotales / 365.25);
                    $diasRestantes = $diasTotales - ($añosEnteros * 365.25);
                    $semanas = round($diasRestantes / 7);
                    $mascota->edad = "{$añosEnteros} años y {$semanas} semanas";
                }
                // $mascota->edad = Carbon::parse($mascota->fecha_nacimiento)->age;
            }
            return view('mascota/gestion_mascota', compact('mascotas'));
        } catch (\Exception $e) {
            // Aquí puedes registrar el error o mostrar un mensaje personalizado
            return redirect()->route('gestionmascota')->with('error', 'Error al cargar mascotas');
        }
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        // Validación básica: evita búsquedas vacías
        if (empty($search)) {
            return redirect()->route('gestionmascota')->with('error', 'Ingresa un término de búsqueda');
        } else {
            try {
                // Buscar mascotas asociadas al cliente (por documento del dueño) o por nombre de la mascota
                $mascotas = Mascota::with('dueño:id,documento')
                    ->whereHas('dueño', function ($query) use ($search) {
                        $query->where('documento', 'like', "$search");
                    })
                    ->orWhere('name', 'like', "{$search}%")
                    ->orWhere('id', 'LIKE', "$search")
                    ->paginate(5)
                    ->appends(['search' => $search]);
                foreach ($mascotas as $mascota) {
                    $fechaNacimiento = Carbon::parse($mascota->fecha_nacimiento);
                    $ahora = Carbon::now();
                    $diasTotales = $fechaNacimiento->diffInDays($ahora);
                    if ($diasTotales < 7) {
                        // Menos de una semana: mostrar días
                        $mascota->edad = "{$diasTotales} días";
                    } elseif ($diasTotales < 365) {
                        // Menos de un año: mostrar semanas redondeadas
                        $semanas = round($diasTotales / 7);
                        $mascota->edad = "{$semanas} semanas";
                    } else {
                        // Un año o más: mostrar años enteros y semanas restantes
                        $añosEnteros = floor($diasTotales / 365.25);
                        $diasRestantes = $diasTotales - ($añosEnteros * 365.25);
                        $semanas = round($diasRestantes / 7);
                        $mascota->edad = "{$añosEnteros} años y {$semanas} semanas";
                    }
                    // $mascota->edad = Carbon::parse($mascota->fecha_nacimiento)->age;
                }
                if ($mascotas->isNotEmpty()) {
                    return view('mascota/gestion_mascota', compact('mascotas'));
                } else {
                    return redirect()->route('gestionmascota')->with('error', 'No se encontraron resultados');
                }
            } catch (\Exception $e) {
                // Aquí puedes registrar el error o mostrar un mensaje personalizado
                return redirect()->route('gestionmascota')->with('error', 'Error en la busqueda');
            }
        }
    }
    public function store(Request $request)
    {
        // dd($request->all());
        // Validación de los datos del formulario
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'dueño' => 'required|string|exists:clientes,documento',
                'especies' => 'required|string|max:255|',
                'raza' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'fechaNacimiento' => 'required|date|before:today',
                'weight' => 'required|numeric|max:255',
                'sex' => 'required|string|max:255',
                'sterilized' => 'nullable|string',
                'vaccinated' => 'nullable|string',
                'diseases' => 'nullable|string|max:255',
                'description' => 'required|string|max:255',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'name.required' => 'Por favor digite el nombre.',
                'dueño.exists' => 'El dueño no se encuentra registrado.',
                'especies.required' => 'Ingrese la especie.',
                'raza.required' => 'Ingrese la raza.',
                'fechaNacimiento.required' => 'Ingrese la fecha de nacimiento.',
                'weight.required' => 'Ingrese el peso.',
                'sex.required' => 'Ingrese el sexo.',
            ]
        );
        if (empty($request->sterilized)) {
            $esterilizado = 'NO';
        } else {
            $esterilizado = $request->sterilized;
        }
        if (empty($request->vaccinated)) {
            $vacunado = 'NO';
        } else {
            $vacunado = $request->vaccinated;
        }
        try {
            if ($request->hasFile('photo')) {
                $rutaFoto = $request->file('photo')->store('mascotas', 'public');
            } else {
                $rutaFoto = null;
            }
            // Crear un nuevo registro
            DB::beginTransaction();
            Mascota::create([
                'id_cliente' => Cliente::where('documento', $request->dueño)->value('id'),
                'name' => $request->name,
                'especie' => $request->especies,
                'raza' => $request->raza,
                'peso' => $request->weight,
                'fecha_nacimiento' => $request->fechaNacimiento,
                'sexo' => $request->sex,
                'esterilizado' => $esterilizado,
                'vacunado' => $vacunado,
                'enfermedades' => $request->diseases,
                'descripcion' => $request->description,
                'ruta_foto' => $rutaFoto,
            ]);
            DB::commit();
            return redirect()->route('gestionmascota')->with('success', 'Mascota registrada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('gestionmascota')->with('error', 'Error al registrar la mascota');
        }
    }
    public function update(Request $request)
    {
        // dd($request->all());
        // Validar datos recibidos
        $request->validate([
            'date' => 'date|before:today',
            'weight' => 'numeric|max:255',
            'sterilized' => 'nullable|string',
            'vaccinated' => 'nullable|string',
            'diseases' => 'nullable|string|max:255',
            'description' => 'string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        try {
            $mascota = Mascota::findOrFail($request->idmascota);
            // Verificar si subieron nueva foto
            if ($request->hasFile('photo')) {
                // Si ya tiene una foto guardada, la borramos del storage
                if ($mascota->ruta_foto && Storage::disk('public')->exists($mascota->ruta_foto)) {
                    Storage::disk('public')->delete($mascota->ruta_foto);
                }
                // Guardar la nueva
                $rutaFoto = $request->file('photo')->store('mascotas', 'public');
            } else {
                // Mantener la foto actual
                $rutaFoto = $mascota->ruta_foto;
            }
            // Actualizar campos
            DB::beginTransaction();
            $mascota->update([
                'fecha_nacimiento' => $request->date,
                'peso' => $request->weight,
                'esterilizado' => $request->sterilized,
                'vacunado' => $request->vaccinated,
                'enfermedades' => $request->diseases,
                'descripcion' => $request->description,
                'ruta_foto' => $rutaFoto,
            ]);
            DB::commit();
            return redirect()->route('gestionmascota')->with('success', 'Mascota actualizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('gestionmascota')->with('error', 'Error al actualizar la mascota');
        }
    }
}
