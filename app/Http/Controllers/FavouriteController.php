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

        return response()->json(['message' => 'Novela añadida a favoritos.']);
    }

    //Eliminar novela de favoritos
    public function destroy(Novel $novel)
    {
        $user = Auth::user();
        $user->favouriteNovels()->detach($novel->id);

        return response()->json(['message' => 'Novela eliminada de favoritos.']);
    }

    //Listar novelas favoritas
    public function index()
    {
        $user = Auth::user();
        $favouriteNovels = $user->favouriteNovels()->with('creator')->get();

        return response()->json($favouriteNovels);
    }
}
