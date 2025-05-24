<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;

class ChapterController extends Controller
{   
    //Busca un capítulo en una novela específica
    private function findChapterInNovel(Novel $novelId, Chapter $chapterId)
    {
        $chapter = Chapter::where('novel_id', $novelId)->where('id', $chapterId)->first();
        if (!$chapter) {
            return response()->json(['message' => 'Capítulo no encontrado'], 404);
        }
        return $chapter;
    }

    //Comprueba el rol del usuario, ya que solo los creadores y administradores pueden actualizar, guardar y eliminar capítulos
    protected function authorizeCreatorOrAdmin()
    {
        $user = auth()->user();
        if ($user->role !== 'creator' && $user->role !== 'admin') {
            abort(response()->json(['message' => 'No autorizado'], 403));
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Novel $novel)
    {   
        $chapters = Chapter::where('novel_id', $novel->id)->get();
        if ($chapters->isEmpty()) {
            return response()->json(['message' => 'No hay capítulos disponibles para esta novela'], 404);
        }
        return response()->json($chapters);
        //Devuelve todos los capítulo existentes en la base de datos
        //return Chapter::all();
    }

    /**
     * Display the specified resource.
     */
    public function show(Novel $novel, Chapter $chapter)
    {   
        $chapterInNovel = $this->findChapterInNovel($novel->id, $chapter->id);
        
        // Return a specific chapter
        return response()->json($chapterInNovel);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChapterRequest $request, Novel $novel)
    {   
        
        $this->authorizeCreatorOrAdmin();
        
        $exists = Chapter::where('novel_id', $novel->id)
            ->where('title', $request->title)
            ->exists();
        if ($exists) {
            return response()->json(['message' => 'El capítulo ya existe en esta novela'], 409);
        }

        // Validate and create a new chapter
        $validatedData = $request->validated();
        $validatedData['novel_id'] = $novel->id; // Associate the chapter with the novel
        $chapter = Chapter::create($validatedData);

        return response()->json($chapter, 201);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChapterRequest $request, Novel $novel, Chapter $chapter)
    {   
        $this->authorizeCreatorOrAdmin();
        $chapterInNovel = $this->findChapterInNovel($novel->id, $chapter->id);
        // Valida y actualiza el capítulo
        $validatedData = $request->validated();
        $chapterInNovel->update($validatedData);

        return response()->json($chapterInNovel, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Novel $novel, Chapter $chapter)
    {   
        $this->authorizeCreatorOrAdmin();
        $chapterInNovel = $this->findChapterInNovel($novel->id, $chapter->id);
        
        $chapterInNovel->delete();

        return response()->json(null, 204);
    }

    public function uploadMedia(Request $request, Chapter $chapter)
    {
        $this->authorizeCreatorOrAdmin();

        // Handle media upload logic here
        // Example: $request->file('media')->store('chapters');

        return response()->json(['message' => 'Media uploaded successfully']);
    }
    public function deleteMedia(Request $request, Chapter $chapter, $mediaId)
    {
        $this->authorizeCreatorOrAdmin();

        // Handle media deletion logic here
        // Example: Storage::delete('chapters/' . $mediaId);

        return response()->json(['message' => 'Media deleted successfully']);
    }
}
