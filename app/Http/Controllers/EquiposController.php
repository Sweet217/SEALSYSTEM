<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\equipos;

class EquiposController extends Controller
{
    /**
     * Display a listing of the equipos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showEquipos(Request $request)
    {
        $data = Equipos::all();

        return Inertia::render('Equipos', [
            'equipos' => $data
        ]);
    }

    /**
     * Store a newly created equipo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function crearEquipos(Request $request)
    {
        $request->validate([
            'licencia_id' => 'nullable|integer',
            'usuario_id' => 'required|integer',
            'nombre' => 'required|string'
            // Add other fields and validation rules as necessary
        ]);

        $equipo = new Equipos();
        $equipo->licencia_id = $request->licencia_id;
        $equipo->usuario_id = $request->usuario_id;
        $equipo->nombre = $request->nombre;
        // Set other fields as necessary

        $equipo->save();

        return response()->json(['message' => 'Equipo creado correctamente'], 201);
    }

    /**
     * Remove the specified equipo from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $equipo_id
     * @return \Illuminate\Http\Response
     */
    public function borrarEquipos(Request $request, $equipo_id)
    {
        $equipo = Equipos::find($equipo_id);

        if (!$equipo) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        $equipo->delete();

        return response()->json(['message' => 'Equipo eliminado correctamente'], 200);
    }

    /**
     * Update the specified equipo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $equipo_id
     * @return \Illuminate\Http\Response
     */
    public function editarEquipos(Request $request, $equipo_id)
    {
        $request->validate([
            'licencia_id' => 'nullable|integer',
            'usuario_id' => 'required|integer',
            'nombre' => 'required|string'
            // Add other fields and validation rules as necessary
        ]);

        $equipo = Equipos::find($equipo_id);

        if (!$equipo) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        $equipo->licencia_id = $request->licencia_id;
        $equipo->usuario_id = $request->usuario_id;
        $equipo->nombre = $request->nombre;
        // Update other fields as necessary

        $equipo->save();

        return response()->json(['message' => 'Equipo editado correctamente'], 200);
    }
}