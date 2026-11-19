<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        // Al crear, la imagen principal es obligatoria; al editar es opcional
        $creando = $this->isMethod('post');

        return [
            'title' => ['required', 'string', 'min:5', 'max:200'],
            'category_id' => ['required', 'exists:categories,id'],
            'activity_date' => ['required', 'date'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'content' => ['required', 'string', 'min:100'],
            'status' => ['required', Rule::in(['borrador', 'publicado'])],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['exists:users,id'],
            'cover_image' => [$creando ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery' => ['nullable', 'array', 'max:12'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'min.string' => 'El campo :attribute debe tener al menos :min caracteres.',
            'max.string' => 'El campo :attribute no debe superar :max caracteres.',
            'exists' => 'El valor seleccionado en :attribute no es válido.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'in' => 'El valor de :attribute no es válido.',
            'authors.min' => 'Selecciona al menos una autora.',
            'image' => 'El archivo de :attribute debe ser una imagen.',
            'mimes' => 'Las imágenes deben ser JPG, PNG o WEBP.',
            'max.file' => 'Cada imagen debe pesar como máximo 5 MB.',
            'gallery.max' => 'Puedes subir hasta 12 imágenes a la vez.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'category_id' => 'categoría',
            'activity_date' => 'fecha de la actividad',
            'excerpt' => 'resumen',
            'content' => 'contenido',
            'status' => 'estado',
            'authors' => 'autoras',
            'cover_image' => 'imagen principal',
            'gallery.*' => 'galería',
        ];
    }
}