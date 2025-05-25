<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{   
//POR HACER:
//- Custom request para la validación de los datos del usuario
//- Enviar enlace de restablecimiento de contraseña al correo electrónico del usuario
//- Implementar la lógica para enviar el enlace de restablecimiento de contraseña


    //Registra un nuevo usuario
    public function register(Request $request)
    {
        try {
            // Validación de los datos del usuario
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
                'role' => 'required|in:user,creator,admin',
            ]);
            
            // Creación del usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Generar un token de acceso para el usuario, necesario para sactum
            $token = $user->createToken('auth_token')->plainTextToken;
            
            // Respuesta
            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el usuario: ' . $e->getMessage(),
                'data' => null
            ], 500);
        } 
    }

    public function login(Request $request)
    {
        // Lógica para autenticar al usuario
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas.',
                'data' => null
            ], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Usuario autenticado exitosamente',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 200);
    }

    //Cierra sesión del usuario actual
    public function logout(Request $request)
    {
        $user = $request->user();
        //Cerrar sesión en todos los dispositivos
        //$user->tokens()->delete();

        //Cerrar sesión actual 
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente',
            'data' => null
        ], 200);
    }

    //Actualiza el perfil del usuario autenticado, pudiendo actualizar el nombre y email
    public function update(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        $user->update($request->only('name', 'email'));
        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado exitosamente', 
            'data' => [
                'user' => $user
            ]
        ], 200);
    }

    //Enviar enlace de recuperación de contraseña
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|string|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
                'data' => null
            ], 404);
        }
        // Aquí se debería enviar un enlace de restablecimiento de contraseña al correo electrónico del usuario, lo dejo como algo opcional a fututo
        return response()->json([
            'success' => true,
            'message' => 'Enlace de restablecimiento de contraseña enviado',
            'data' => [
                'email' => $user->email
            ]
        ], 200);
    }

    //Restablecer la contraseña del usuario
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
                'data' => null
            ], 404);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Contraseña restablecida exitosamente',
            'data' => [
                'user' => $user
            ]
        ], 200);
    }
    
}
