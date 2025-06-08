<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Novel;
use App\Models\User;

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

    //Obtener la lista de todos los usuarios para el panel de control
    public function users(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para acceder a esta sección',
                'data' => null
            ], 403);
        }

        $allUsers = User::latest()->get(); // Obtener todos los usuarios ordenados por fecha de creación reciente
        return response()->json([
            'success' => true,  
            'message' => 'Usuarios obtenidos correctamente.',
            'data' => [
                'users' => $allUsers
            ]
        ], 200);
    }

    //Función para borrar un usuario
    public function destroyUser(Request $request, User $user)
    {
        $authUser = Auth::user();
        if ($authUser->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para acceder a esta sección',
                'data' => null
            ], 403);    
        }
        // Verificar si el usuario a eliminar es el mismo que el autenticado
        if ($authUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propio usuario.',
                'data' => null
            ], 403);
        }
        // Eliminar el usuario
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente.',
            'data' => null
        ], 200);
    }
}
