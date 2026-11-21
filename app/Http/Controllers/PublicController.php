<?php

namespace App\Http\Controllers;

use App\Models\category_model;
use App\Models\post_model;
use App\Models\User;

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

        // Integrantes con sus entradas publicadas y evidencias (se buscan por correo)
        $equipo = User::withCount([
            'authoredPosts as entradas' => fn ($q) => $q->where('status', 'publicado'),
            'evidences as evidencias',
        ])
            ->get()
            ->keyBy('email');

        return view('public.home', compact('categories', 'latest', 'equipo'));
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