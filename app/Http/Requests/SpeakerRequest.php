<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpeakerRequest extends FormRequest
{
    // El acceso ya lo protege el middleware 'admin' en las rutas.
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'job_title'        => ['nullable', 'string', 'max:255'],
            'specialty'        => ['nullable', 'string', 'max:255'],
            'institution'      => ['required', 'string', 'max:255'],
            'city'             => ['nullable', 'string', 'max:255'],
            'country'          => ['required', 'string', 'max:255'],
            'thematic_axis_id' => ['nullable', 'exists:thematic_axes,id'],
            'talk_title'       => ['nullable', 'string', 'max:255'],
            'bio'              => ['nullable', 'string', 'max:5000'],
            'sort_order'       => ['nullable', 'integer', 'min:0', 'max:65000'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'nombre',
            'job_title'        => 'cargo',
            'specialty'        => 'especialidad',
            'institution'      => 'institución',
            'city'             => 'ciudad',
            'country'          => 'país',
            'thematic_axis_id' => 'eje temático',
            'talk_title'       => 'título de la ponencia',
            'bio'              => 'biografía',
            'sort_order'       => 'orden',
            'photo'            => 'foto',
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => 'El campo :attribute es obligatorio.',
            'string'      => 'El campo :attribute debe ser texto.',
            'max.string'  => 'El campo :attribute no puede tener más de :max caracteres.',
            'max.file'    => 'La :attribute no puede pesar más de :max KB.',
            'integer'     => 'El campo :attribute debe ser un número entero.',
            'min.numeric' => 'El campo :attribute debe ser :min o más.',
            'max.numeric' => 'El campo :attribute no puede ser mayor que :max.',
            'exists'      => 'El :attribute seleccionado no es válido.',
            'image'       => 'La :attribute debe ser una imagen.',
            'mimes'       => 'La :attribute debe ser un archivo JPG, PNG o WEBP.',
        ];
    }
}