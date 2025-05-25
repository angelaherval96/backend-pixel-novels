<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Reading;
use App\Models\Chapter;
use App\Http\Requests\StoreReadingRequest;
use App\Http\Requests\UpdateReadingRequest;

class ReadingController extends Controller
{
    //Obtener todas las lecturas del usuario autenticado
    public function index()
    {
        $user = auth()->user();
        $readings = Reading::where('user_id', $user->id)->with('chapter.novel')->get();

        if ($readings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay lecturas registradas.',
                'data' => [
                    'readings' => []
                ]
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lecturas obtenidas correctamente.',
            'data' => [
                'readings' => $readings
            ]
        ], 200);
        
    }

    //Crear o actualizar una lectura
    public function storeOrUpdate(Request $request, Chapter $chapter)
    {   
        $request->validate([
            'progress' => 'required|integer|min:0|max:1',
            'read_at' => 'nullable|date',
        ]);

        $user = auth()->user();

        $reading = Reading::updateOrCreate(
            [
                'user_id' => $user->id, 
                'chapter_id' => $chapter->id
            ],
            [
                'progress' => $request->input('progress', 0),
                'read_at' => $request->input('read_at', now())
            
            ]);

        return response()->json([
            'success' => true,
            'message'=> 'Progreso guardado correctamente.', 
            'data'=> [
               'readings' => $reading 
            ]
        ], 201);
    }
   
    //Muestra una lectura específica del usuario autenticado
    public function show(Reading $reading)
    {
        $user = auth()->user();

        // Verifica si la lectura pertenece al usuario autenticado
        if ($reading->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para ver esta lectura.',
                'data' => null
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lectura obtenida correctamente.',
            'data' => [
                'reading' => $reading
            ]
        ], 200); 
    }

    //Elimina una lectura del usuario autenticado
    public function destroy(Reading $reading)
    {
        $user = auth()->user();

        // Verifica si la lectura pertenece al usuario autenticado
        if ($reading->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para eliminar esta lectura.',
                'data' => null
            ], 403);
        }

        $reading->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lectura eliminada correctamente.',
            'data' => null
        ], 200);
    }
}
