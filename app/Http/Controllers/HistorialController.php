<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\User;


class HistorialController extends Controller
{
    public function index()
    {
        try {
            $usuarios = User::where('id_role', 3)->where('active', 1)->select('id', 'name')->get();
            $citas = Cita::with('cliente', 'mascota', 'usuario')->orderBy('id', 'desc')->paginate(5);
            return view('cita/gestion_historial_cita', compact('citas', 'usuarios'));
        } catch (\Exception $e) {
            // Aquí puedes registrar el error o mostrar un mensaje personalizado
            return redirect()->route('gestionhistorial')->with('error', 'Error al cargar las citas');
        }
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestionhistorial')->with('error', 'Ingresa un término de búsqueda');
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
                    return redirect()->route('gestionhistorial')->with('error', 'No se encontraron resultados');
                }
            } catch (\Exception $e) {
                return redirect()->route('gestionhistorial')->with('error', 'Error en la busqueda');
            }
        }
    }
}
