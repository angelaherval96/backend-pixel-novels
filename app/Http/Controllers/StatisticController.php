<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use App\Models\Novel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStatisticRequest;
use App\Http\Requests\UpdateStatisticRequest;

class StatisticController extends Controller
{   
    protected function authorizeCreatorOrAdmin(Novel $novel)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return;
        }
        if ($user->role === 'creator' && $novel->user_id === $user->id) {
            return;
        }
        else{
            abort(403, 'No autorizado');
        }
       
    }

    //Mostrar las estadísticas de todas las novela
    public function index()
    {
        $statistics = Statistic::with('novel')->get();
        if ($statistics->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay estadísticas registradas.',
                'data' => null
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Estadísticas obtenidas correctamente.',
            'data' => [
                'statistic' => $statistics
            ]
        ], 200);
    }

    //Crear una estadística para una novela
    public function store(StoreStatisticRequest $request, Novel $novel)
    {   
        $this->authorizeCreatorOrAdmin($novel);

        if($novel->statistic){
            return response()->json([
                'success' => false,
                'message' => 'Las estadísticas ya existen para esta novela.',
                'data' => null
            ], 400);
        }

        //Creamos la estadística
        $statistic = Statistic::create([
            'novel_id' => $novel->id,
            'views' => $request->input('views', 0),
            'likes' => $request->input('likes', 0),
            'shares' => $request->input('shares', 0),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estadísticas creadas.', 
            'data' => [
                'statistic' => $statistic
            ]
        ], 201);
    }

    //Mostrar estadísticas de una novela específica
    public function show(Novel $novel)
    {
        $statistic = $novel->statistic;
        if (!$statistic) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron estadísticas para esta novela.',
                'data' => null
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Estadísticas obtenidas correctamente.',
            'data' => [
                'statistic' => $statistic
            ]
        ], 200);
    }

    //Actualizar contadores de una estadística (likes, views, shares)
    public function update(Request $request, Novel $novel)
    {   
        $this->authorizeCreatorOrAdmin($novel);
        $request->validate([
            'views' => 'nullable|integer|min:0',
            'likes' => 'nullable|integer|min:0',
            'shares' => 'nullable|integer|min:0',
        ]);
        $statistic = $novel->statistic;
        if (!$statistic) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron estadísticas para esta novela.',
                'data' => null
            ], 404);
        }
        
        $statistic->update($request->only(['views', 'likes', 'shares']));
        return response()->json([
            'success' => true,
            'message' => 'Estadísticas actualizadas.', 
            'data' => [
                'statistic' => $statistic
            ]
        ], 200);
    }
}
