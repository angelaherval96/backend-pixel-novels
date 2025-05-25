<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;

class ChapterController extends Controller
{   
    //Busca un capítulo en una novela específica
    private function findChapterInNovel(int $novelId, int $chapterId)
    {
        $chapter = Chapter::where('novel_id', $novelId)->where('id', $chapterId)->first();
        if (!$chapter) {
            return response()->json([
                'success' => false,
                'message' => 'Capítulo no encontrado',
                'data' => null
            ], 404); // 404 Not Found, capítulo no encontrado
        }
        return $chapter;
    }

    //Comprueba el rol del usuario, ya que solo los creadores y administradores pueden actualizar, guardar y eliminar capítulos
    protected function authorizeCreatorOrAdmin()
    {
        $user = auth()->user();
        if ($user->role !== 'creator' && $user->role !== 'admin') {
            abort(403, 'No autorizado'); // 403 Forbidden, el usuario no tiene permiso para realizar esta acción
        }
    }

    //Lista todos los capítulos de una novela específica
    public function index(Novel $novel)
    {   
        $chapters = Chapter::where('novel_id', $novel->id)->get();
        if ($chapters->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay capítulos disponibles para esta novela.',
                'data' => null
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Capítulos obtenidos correctamente.',
            'data' => [
                'chapters' => $chapters
            ]
            ], 200); // 200 OK, éxito al obtener los capítulos

        //Devuelve todos los capítulo existentes en la base de datos
        //return Chapter::all();
    }

    //Muestra un capítulo específico de una novela en concreto
    public function show(Novel $novel, Chapter $chapter)
    {   
        $chapterInNovel = $this->findChapterInNovel($novel->id, $chapter->id);

        return response()->json([
            'success' => true,
            'message' => 'Capítulo obtenido correctamente.',
            'data' => [
                'chapter' => $chapterInNovel
            ]
        ], 200);
    }

    
    //Almacena un nuevo capítulo en una novela específica
    public function store(StoreChapterRequest $request, Novel $novel)
    {   
        
        $this->authorizeCreatorOrAdmin();
        
        $exists = Chapter::where('novel_id', $novel->id)
            ->where('title', $request->title)
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'El capítulo ya existe en esta novela',
                'data' => null
            ], 409); //409 conflicto de unicidad (capítulo ya existe)
        }

        // Valida y crea el capítulo
        $validatedData = $request->validated();
        $validatedData['novel_id'] = $novel->id; // Asociar el capítulo a la novela
        $chapter = Chapter::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Capítulo creado correctamente.',
            'data' => [
                'chapter' => $chapter
            ]
        ], 201);
    }

    
    //Actualiza un capítulo específico de una novela
    public function update(UpdateChapterRequest $request, Novel $novel, Chapter $chapter)
    {   
        $this->authorizeCreatorOrAdmin();
        $chapterInNovel = $this->findChapterInNovel($novel->id, $chapter->id);
        // Valida y actualiza el capítulo
        $validatedData = $request->validated();
        $chapterInNovel->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Capítulo actualizado correctamente.',
            'data' => [
                'chapter' => $chapterInNovel
            ]
        ], 200);
    }

    //Elimina un capítulo específico de una novela
    public function destroy(Novel $novel, Chapter $chapter)
    {   
        $this->authorizeCreatorOrAdmin();
        $chapterInNovel = $this->findChapterInNovel($novel->id, $chapter->id);
        
        $chapterInNovel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Capítulo eliminado correctamente.',
            'data' => null
        ], 200);
    }

}

