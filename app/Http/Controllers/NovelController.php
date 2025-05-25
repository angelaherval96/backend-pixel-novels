<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreNovelRequest;
use App\Http\Requests\UpdateNovelRequest;


class NovelController extends Controller
{   
    //Comprueba el rol del usuario, ya que solo los creadores (si la novela es suya) y administradores pueden actualizar, guardar y eliminar novelas
    protected function authorizeCreatorOrAdmin(Novel $novel = null)
    {
        $user = auth()->user();
        if($user->role === "admin") {
            return; // Los administradores tienen acceso sin restricciones
        }
        if ($user->role === "creator" && $novel && $novel->user_id === $user->id) {
            return; // El creador de la novela tiene acceso sin restricciones
        }
        else
        {   
            // Si el usuario no es ni creador ni administrador, se aborta la solicitud con un error 403
            abort(403, 'No autorizado');
        }
        
    }

    //Listar todas las novelas
    public function index()
    {
        $novels = Novel::with('creator')->get();
        return response()->json($novels);
    }

    //Guardar una nueva novela
    public function store(StoreNovelRequest $request)
    {
        $this->authorizeCreatorOrAdmin();

        $validated = $request->validated();

        // Verifica si el usuario ya tiene una novela con el mismo título -> PASARLO AL CUSTOM REQUEST
        $exists = Novel::where('user_id', Auth::id())->where('title', $validated['title'])->exists();
        if ($exists) {
            return response()->json(['message' => 'Ya tienes una novela con este título.'], 422);
        }

        // Asignar el ID del usuario creador
        $validated['user_id'] = Auth::id(); 
        $novel = Novel::create($validated);
        return response()->json($novel, 201);
    }

    //Mostrar una novela específica con sus capítulos y creador
    public function show(Novel $novel)
    {
        return response()->json($novel->load(['creator', 'chapters']));
    }

    //Actualizar una novela específica
    public function update(UpdateNovelRequest $request, Novel $novel)
    {   
        // Verifica si el usuario es el creador de la novela o un administrador
        $this->authorizeCreatorOrAdmin($novel);
        $novel->update($request->validated());
        return response()->json($novel);
    }

    //Eliminar una novela específica
    public function destroy(Novel $novel)
    {
        $this->authorizeCreatorOrAdmin($novel);
        $novel->delete();
        return response()->json(['message' => 'Novela eliminada correctamente.'], 200);
    }

}
