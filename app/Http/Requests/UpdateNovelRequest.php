<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNovelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        //Si no hay usuario autenticado
        if (!$user || !$novel){
            return false;
        }

        if($user->role === "admin") {
            return true; // Los administradores tienen acceso sin restricciones
        }
        if ($user->role === "creator" && $novel->user_id === $user->id) {
            return true; // El creador solo puede editar sus novelas
        }
        else
        {   
            // Si el usuario no es ni creador ni administrador, se aborta la solicitud con un error 403
            abort(403, 'No autorizado');
        }
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
