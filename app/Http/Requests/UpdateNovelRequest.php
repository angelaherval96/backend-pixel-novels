<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateNovelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        $novel = $this->route('novel'); // Obtiene la novela de la ruta actual

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
            'title' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('novels', 'title') //Debe ser único en la tabla 'novels' para el campo 'title'
                ->where(function ($query) {
                    return $query->where('user_id', $this->user()->id); // Asumiendo que el campo es creator_id
                }) -> ignore($this->route('novel')->id),  // Ignora la novela actual para evitar conflictos al actualizar
                //Regla para asegurar que el título sea único por usuario.
            ],
            'description' => 'nullable|string',
            'language' => 'sometimes|string|max:10',
            'cover' => 'sometimes|string|url',  // Si es un archivo subido, la validación es diferente (ej. 'image|mimes:jpg,png|max:2048')
        ];
    }
}
