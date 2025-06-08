<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
    protected function authorizeCreatorOrAdmin(Novel $novel = null)
    {
        $user = auth()->user(); // o request()->user()

        if (!$user) { // Siempre es buena idea verificar si hay usuario
            abort(401, 'No autenticado');
        }

        if ($user->role === 'admin') {
            return; // El admin puede hacer todo
        }

        // Si es un creador, necesita ser el dueño de la novela específica
        if ($user->role === 'creator') {
            if ($novel && $novel->creator_id === $user->id) { // Asumiendo que Novel tiene creator_id
                return;
            }
        }
        
        abort(403, 'No autorizado');
    }

    //Lista todos los capítulos de una novela específica
    public function index(Novel $novel)
    {   
        //Ordenar los capítulos por orden de forma ascendente
        $chapters = Chapter::where('novel_id', $novel->id)->get();
        if ($chapters->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay capítulos disponibles para esta novela.',
                'data' => []
            ], 200);
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
        // $chapterInNovel = $this->findChapterInNovel($novel->id, $chapter->id);
        
         //Verifica que el capítulo pertenezca a la novela
        if ($chapter->novel_id !== $novel->id){
             return response()->json([
                'success' => false,
                'message' => 'Capítulo no encontrado en esta novela.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Capítulo obtenido correctamente.',
            'data' => [
                'chapter' => $chapter //$chapterInNovel
            ]
        ], 200);
    }

    
    //Almacena un nuevo capítulo en una novela específica
    public function store(StoreChapterRequest $request, Novel $novel)
    {   
        
        //$this->authorizeCreatorOrAdmin();
        
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
        //$this->authorizeCreatorOrAdmin();
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

    //Función para subir archivos multimedia (imágenes, vídeos, etc.) asociados a un capítulo
    public function uploadMedia(Request $request)
    {
        $request->validate([
            //'media-file' es el nombre que se le dará al campo en el frontend
            'media_file' => 'required|file|mimetypes:video/mp4, image/jpg,image/jpeg,image/png,image/gif|max:20480', // 20MB max
        ]);
        //Guardar el archivo en el sistema de archivos
        $file = $request->file('media_file');
        $path = $file->store('media', 'public'); // Guardar en el disco 'public' en la carpeta 'media'

        $url = asset(Storage::url($path)); // Obtener la URL pública del archivo guardado
        return response()->json([
            'success' => true,
            'message' => 'Archivo multimedia subido correctamente.',
            'data' => [
                'url' => $url // Devuelve la ruta del archivo guardado
            ]
        ], 200);
    }
}

