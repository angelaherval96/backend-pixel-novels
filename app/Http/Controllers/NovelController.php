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
        return response()->json([
            'success' => true,
            'message' => 'Novelas obtenidas correctamente.',
            'data' => [
                'novels' => $novels
            ]
        ], 200);
    }

    //Guardar una nueva novela
    public function store(StoreNovelRequest $request)
    {

        $validated = $request->validated();

        // Verifica si el usuario ya tiene una novela con el mismo título -> PASARLO AL CUSTOM REQUEST
        $exists = Novel::where('user_id', Auth::id())->where('title', $validated['title'])->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes una novela con este título.',
                'data' => null
            ], 422);// 422 Unprocessable Entity, ya existe una novela con el mismo título
        }

        // Asignar el ID del usuario creador
        $validated['user_id'] = Auth::id(); 
        $novel = Novel::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Novela creada correctamente.',
            'data' => [
                'novel' => $novel
            ]
        ], 201);
    }

    //Mostrar una novela específica con sus capítulos y creador
    public function show(Novel $novel)
    {
        return response()->json([
            'success' => true,
            'message' => 'Novela obtenida correctamente.',
            'data' => [
                'novel' => $novel->load(['creator', 'chapters'])
            ]
        ], 200);
    }

    //Actualizar una novela específica
    public function update(UpdateNovelRequest $request, Novel $novel)
    {   
        // Verifica si el usuario es el creador de la novela o un administrador
        //$this->authorizeCreatorOrAdmin($novel);
        $novel->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Novela actualizada correctamente.',
            'data' => [
                'novel' => $novel
            ]
        ], 200);
    }

    //Eliminar una novela específica
    public function destroy(Novel $novel)
    {
        $this->authorizeCreatorOrAdmin($novel);
        $novel->delete();
        return response()->json([
            'success' => true,
            'message' => 'Novela eliminada correctamente.',
            'data' => null
        ], 200);
    }

}
