<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\category_model;
use App\Models\evidence_model;
use App\Models\post_model;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Listado con filtros
    public function index(Request $request)
    {
        $posts = post_model::with(['category', 'authors'])
            ->withCount(['images', 'evidences'])
            ->when($request->filled('estado'), fn ($q) => $q->where('status', $request->estado))
            ->when($request->filled('categoria'), fn ($q) => $q->where('category_id', $request->categoria))
            ->when($request->filled('buscar'), fn ($q) => $q->where('title', 'ilike', '%'.$request->buscar.'%'))
            ->orderByDesc('activity_date')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $categories = category_model::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    // Formulario para crear
    public function create()
    {
        return view('admin.posts.create', [
            'post' => new post_model(['status' => 'borrador', 'activity_date' => now()]),
            'categories' => category_model::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    // Guardar nueva entrada
    public function store(PostRequest $request)
    {
        $data = $request->validated();

        $post = post_model::create([
            'user_id' => $request->user()->id,
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'slug' => post_model::uniqueSlug($data['title']),
            'activity_date' => $data['activity_date'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'status' => $data['status'],
            'cover_image' => $request->file('cover_image')->store('entradas/portadas', 'public'),
        ]);

        $post->authors()->sync($data['authors']);
        $this->guardarGaleria($request, $post);

        return redirect()->route('admin.posts.edit', $post)
            ->with('success', 'Entrada creada correctamente. Ahora puedes agregar las evidencias de asistencia.');
    }

    // Formulario para editar 
    public function edit(post_model $post)
    {
        $post->load(['images', 'authors', 'evidences.user']);

        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => category_model::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'evidenceTypes' => evidence_model::TYPES,
        ]);
    }

    // Guardar cambios
    public function update(PostRequest $request, post_model $post)
    {
        $data = $request->validated();

        // Si suben otra imagen principal, se borra la anterior
        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $post->cover_image = $request->file('cover_image')->store('entradas/portadas', 'public');
        }

        if ($post->title !== $data['title']) {
            $post->slug = post_model::uniqueSlug($data['title'], $post->id);
        }

        $post->fill([
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'activity_date' => $data['activity_date'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'status' => $data['status'],
        ])->save();

        $post->authors()->sync($data['authors']);
        $this->guardarGaleria($request, $post);

        return redirect()->route('admin.posts.edit', $post)
            ->with('success', 'Entrada actualizada correctamente.');
    }

    // Eliminar entrada con todos sus archivos
    public function destroy(post_model $post)
    {
        $archivos = collect([$post->cover_image])
            ->merge($post->images()->pluck('path'))
            ->merge($post->evidences()->pluck('file'))
            ->filter()
            ->all();

        Storage::disk('public')->delete($archivos);
        $post->delete(); 

        return redirect()->route('admin.posts.index')
            ->with('success', 'Entrada eliminada correctamente.');
    }

    // Guarda las img 
    private function guardarGaleria(Request $request, post_model $post): void
    {
        foreach ($request->file('gallery', []) as $imagen) {
            $post->images()->create([
                'path' => $imagen->store('entradas/galeria', 'public'),
            ]);
        }
    }
}