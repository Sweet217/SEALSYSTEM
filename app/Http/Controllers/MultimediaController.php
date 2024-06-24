<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\multimedia;
use App\Models\listas;
use Inertia\Inertia;
use App\Models\videos;
use App\Models\enlaces;
use App\Models\imagenes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MultimediaController extends Controller
{   
    /**
      * Muestra los elementos multimedia de una lista.
      *
      * @param Request $request Objeto de solicitud HTTP.
      * @param int $id_lista Identificador de la lista a la que pertenecen los elementos multimedia.
      * @return \Illuminate\Http\Response Respuesta JSON con los datos de la lista y los elementos multimedia.
      */
    public function showMultimedia(Request $request, $id_lista)
    {
    //  Obtener la lista
    $lista = Listas::find($id_lista); // Busca la lista con el identificador especificado.

    if (!$lista) {
        // Si la lista no existe, devuelve una respuesta de error 404.
        return response()->json(['error' => 'Invalid list ID'], 404);
    }

    // Obtener los elementos multimedia
    $multimediaItems = $lista->multimedia()->with(['video', 'imagen', 'enlace'])->orderBy('posicion')->get(); // Obtiene los elementos multimedia de la lista, incluyendo los datos de video, imagen o enlace.

    // Transformar los datos de los elementos multimedia
    $multimediaData = $multimediaItems->map(function($item) { // Transforma cada elemento multimedia en un nuevo objeto con la estructura especificada.
        if ($item->tipo == 'video' && $item->video) {
        return [ // Si el tipo es video y existe el video, devuelve un objeto con la siguiente estructura:
            'tipo' => 'video',
            'data' => $item->video,
        ];
        } elseif ($item->tipo == 'imagen' && $item->imagen) {
        return [ // Si el tipo es imagen y existe la imagen, devuelve un objeto con la siguiente estructura:
            'tipo' => 'imagen',
            'data' => $item->imagen,
        ];
        } elseif ($item->tipo == 'enlace' && $item->enlace) {
        return [ // Si el tipo es enlace y existe el enlace, devuelve un objeto con la siguiente estructura:
            'tipo' => 'enlace',
            'data' => $item->enlace,
        ];
        }
        return null; // Si no se encuentran datos para el tipo de elemento multimedia, devuelve `null`.
    })->filter(); // Elimina los elementos `null` del array.

    // Renderizar la vista y devolver la respuesta
    return Inertia::render('Multimedia', [ // Renderiza la vista `Multimedia` de Inertia y devuelve la respuesta JSON.
        'listaData' => $lista, // Datos de la lista obtenidos en el paso 1.
        'multimedia' => $multimediaData, // Array de objetos con la estructura transformada en el paso 3.
        'multimediaData' => $multimediaItems // Array original de objetos Multimedia obtenidos en el paso 2.
    ]);
    }
    /**
      * Crea un nuevo elemento multimedia en una lista.
      *
      * @param Request $request Objeto de solicitud HTTP.
      * @return \Illuminate\Http\Response Respuesta JSON con un mensaje de éxito y el objeto multimedia creado.
      */
    public function crearMultimedia(Request $request) 
    {
        // Validar los datos recibidos del formulario
        $data = $request->validate([ // Valida los datos del formulario mediante el método `validate()`.
            'tipo' => 'required', // El campo `tipo` es obligatorio y debe ser una cadena de texto.
            'id_lista' => 'required|exists:listas,id_lista', // El campo `id_lista` es obligatorio y debe existir en la tabla `listas` con el nombre de columna `id_lista`.
        ]);

        // Crear la multimedia
        $multimedia = Multimedia::create([ // Crea un nuevo registro en la tabla `Multimedia` utilizando el método `create()`.
        'tipo' => $data['tipo'], // Establece el valor del campo `tipo` con el valor obtenido de la validación.
        'id_lista' => $data['id_lista'], // Establece el valor del campo `id_lista` con el valor obtenido de la validación.
        ]);
        //Devolver la respuesta JSON
        return response()->json(['message' => 'Multimedia creada exitosamente' //multimedia se creó correctamente.
        , 'multimedia' => $multimedia], 201); // Código de estado HTTP 201 ("Created") para indicar que la operación se completó con éxito.
    }

    /**
      * Actualiza el orden de los elementos multimedia en una lista.
      *
      * @param Request $request Objeto de solicitud HTTP.
      * @return \Illuminate\Http\Response Respuesta JSON con un mensaje de éxito o error.
      */
    public function actualizarOrden(Request $request)
    {
        // Validar los datos recibidos del formulario
         $data = $request->validate([ // Valida los datos del formulario mediante el método `validate()`.
            'positions' => 'required|array', // El campo `positions` es obligatorio y debe ser un array.
            'positions.*.multimedia_id' => 'required|integer', // Cada elemento del array `positions` debe tener un campo `multimedia_id` obligatorio y de tipo entero.
            'positions.*.nueva_posicion' => 'required|integer', // Cada elemento del array `positions` debe tener un campo `nueva_posicion` obligatorio y de tipo entero.
        ]);

        // Actualizar el orden de los elementos multimedia
        try {
            DB::transaction(function () use ($data) {
                // Obtener todos los multimedia_id de las posiciones recibidas
                $multimediaIds = collect($data['positions'])->pluck('multimedia_id')->toArray(); // Extrae los valores `multimedia_id` del array `positions` y los convierte en un array.
    
                // Establecer temporalmente todas las posiciones a null
                Multimedia::whereIn('multimedia_id', $multimediaIds)->update(['posicion' => null]); // Establece la posición de todos los elementos multimedia con `multimedia_id` en el array `$multimediaIds` a `null`.
    
                // Actualizar las nuevas posiciones de acuerdo al orden recibido
                foreach ($data['positions'] as $position) { // Recorrer cada elemento del array `positions`.
                    $multimedia_id = $position['multimedia_id']; // Obtener el `multimedia_id` del elemento actual.
                    $nueva_posicion = $position['nueva_posicion']; // Obtener la `nueva_posicion` del elemento actual.
    
                    // Actualizar la posición del elemento multimedia
                    Multimedia::where('multimedia_id', $multimedia_id) // Seleccionar el elemento multimedia con el `multimedia_id` actual.
                        ->update(['posicion' => $nueva_posicion]); // Actualizar la posición del elemento multimedia seleccionado con la `nueva_posicion`.
                }
            });
    
            return response()->json(['message' => 'Posiciones actualizadas correctamente'], 200); // Devolver una respuesta JSON con un mensaje de éxito y código de estado HTTP 200.
        } catch (\Exception $e)  // Capturar excepciones en caso de errores. 
        {
            report($e); //Reportar la excepcion en caso de error
            return response()->json(['error' => 'Error al actualizar las posiciones'], 500); // Devolver una respuesta JSON con un mensaje de error y código de estado HTTP 500.
        }

    }
}