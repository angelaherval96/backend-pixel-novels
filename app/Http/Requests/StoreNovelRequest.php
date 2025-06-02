<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreNovelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //Comprueba el rol del usuario, ya que solo los creadores (si la novela es suya) y administradores pueden actualizar, guardar y eliminar novelas
    
        $user = Auth::user();

        //Si no hay usuario autenticado
        if (!$user){
            return false;
        }

        if($user->role === "admin") {
            return true; // Los administradores tienen acceso sin restricciones
        }
        if ($user->role === "creator") {
            return true; // El creador de la novela tiene acceso sin restricciones
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
                'required',
                'string',
                'max:255',
                Rule::unique('novels', 'title')->where(function ($query){
                    return $query->where('user_id', Auth::id());
                })//Regla para asegurar que el título sea único por usuario.
            ],
            'description' => 'nullable|string',
            'language' => 'required|string|max:10',
            'cover' => 'required|string|url',  // Si es un archivo subido, la validación es diferente (ej. 'image|mimes:jpg,png|max:2048')
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título de la novela es obligatorio.',
            'title.unique' => 'Ya tienes una novela con este título.',
            'description.string' => 'La descripción debe ser texto.',
            'language.required' => 'Debes especificar un idioma.',
            'cover.required' => 'Debes proporcionar una portada (URL o path).',
            'cover.url' => 'La portada debe ser una URL válida.',
        ];
    }
}
