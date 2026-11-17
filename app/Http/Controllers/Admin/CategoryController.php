<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\category_model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Mensajes de validación en español
    private array $messages = [
        'name.required' => 'El nombre de la categoría es obligatorio.',
        'name.min' => 'El nombre debe tener al menos 3 caracteres.',
        'name.max' => 'El nombre no debe superar 100 caracteres.',
        'name.unique' => 'Ya existe una categoría con ese nombre.',
        'description.max' => 'La descripción no debe superar 500 caracteres.',
    ];

    // Listado de categorías
    public function index()
    {
        $categories = category_model::withCount('posts')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    // Formulario para crear
    public function create()
    {
        return view('admin.categories.create', ['category' => new category_model()]);
    }

    // Guardar nueva categoría
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ], $this->messages);

        $data['slug'] = Str::slug($data['name']);
        category_model::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    // Formulario para editar
    public function edit(category_model $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Guardar cambios
    public function update(Request $request, category_model $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100',
                Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:500'],
        ], $this->messages);

        $data['slug'] = Str::slug($data['name']);
        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    // Eliminar (solo si no tiene entradas)
    public function destroy(category_model $category)
    {
        if ($category->posts()->exists()) {
            return back()->with('error', 'No se puede eliminar "'.$category->name.'" porque tiene entradas asociadas.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}