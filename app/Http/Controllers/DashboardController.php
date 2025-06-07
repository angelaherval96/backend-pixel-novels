<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Novel;

class DashboardController extends Controller
{
    //Obtener lista de novelas para el panel de control filtrada según el rol del usuario
    public function novels(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado',
                'data' =>null
            ], 401);
        }
        //Consulta de novelas con el creador ordenadas por fecha de creación reciente
        $query = Novel::with('creator')->latest(); 

        if ($user->role === 'creator') {
            //Si es creador, filtrar por sus propias novelas
            $query->where('user_id', $user->id);
        } elseif ($user->role !== 'admin') {
            //Si no es admin, no puede acceder. Si es admin, mostrar todas las novelas
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para acceder a esta sección',
                'data' =>null
            ], 403);
        }

        $novels = $query->get();
        return response()->json([
            'success' => true,
            'message' => 'Novelas obtenidas correctamente.',
            'data' => [
                'novels' => $novels
            ]
        ], 200);
    }
}
