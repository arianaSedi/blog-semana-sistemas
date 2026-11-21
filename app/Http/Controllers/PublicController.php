<?php

namespace App\Http\Controllers;

use App\Models\category_model;
use App\Models\post_model;
use App\Models\User;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $categories = category_model::withCount([
            'posts' => fn ($q) => $q->where('status', 'publicado')
        ])
            ->orderBy('name')
            ->get();

        $latest = post_model::with(['category', 'authors'])
            ->where('status', 'publicado')
            ->orderByDesc('activity_date')
            ->take(3)
            ->get();

        //entradas publicadas y evidencias se buscan por correo
        $equipo = User::withCount([
            'authoredPosts as entradas' => fn ($q) => $q->where('status', 'publicado'),
            'evidences as evidencias',
        ])
            ->get()
            ->keyBy('email');

        return view('public.home', compact('categories', 'latest', 'equipo'));
    }

    // Listado general con busqueda 
    public function index(Request $request)
    {
        $buscar = trim((string) $request->query('q', ''));
        $categoriaSlug = $request->query('categoria');

        $categories = category_model::withCount([
            'posts' => fn ($q) => $q->where('status', 'publicado'),
        ])->orderBy('name')->get();

        $posts = post_model::with(['category', 'authors'])
            ->published()
            ->when($buscar !== '', function ($q) use ($buscar) {
                $q->where(function ($w) use ($buscar) {
                    $w->where('title', 'ilike', "%{$buscar}%")
                        ->orWhere('excerpt', 'ilike', "%{$buscar}%")
                        ->orWhere('content', 'ilike', "%{$buscar}%");
                });
            })
            ->when($categoriaSlug, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $categoriaSlug)))
            ->orderByDesc('activity_date')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        return view('public.entradas', compact('posts', 'categories', 'buscar', 'categoriaSlug'));
    }

    public function category(string $slug)
    {
        $category = category_model::where('slug', $slug)
            ->firstOrFail();

        $posts = $category->posts()
            ->with(['category', 'authors'])
            ->published()
            ->orderByDesc('activity_date')
            ->orderByDesc('id')
            ->paginate(9);

        return view('public.categoria', compact('category', 'posts'));
    }

    public function show(string $slug)
    {
        $post = post_model::with([
            'category',
            'authors',
            'images'
        ])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.entrada', compact('post'));
    }
}