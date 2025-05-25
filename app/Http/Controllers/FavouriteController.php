<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Novel;

class FavouriteController extends Controller
{
    //Agregar novela a favoritos
    public function store(Novel $novel)
    {
        $user = Auth::user();

        if (!$user->favouriteNovels->contains($novel->id)){
            $user->favouriteNovels()->attach($novel->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Novela añadida a favoritos.',
            'data' => [
                'novel' => $novel
            ]
        ], 201);
    }

    //Eliminar novela de favoritos
    public function destroy(Novel $novel)
    {
        $user = Auth::user();
        $user->favouriteNovels()->detach($novel->id);

        return response()->json([
            'success' => true,
            'message' => 'Novela eliminada de favoritos.',
            'data' =>null
        ], 200);
    }

    //Listar novelas favoritas
    public function index()
    {
        $user = Auth::user();
        $favouriteNovels = $user->favouriteNovels()->with('creator')->get();

        return response()->json([
            'success' => true,
            'message' => 'Novelas favoritas obtenidas correctamente.',
            'data' => [
                'favouriteNovels' => $favouriteNovels
            ]
        ], 200);
    }
}
