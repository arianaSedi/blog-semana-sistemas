<?php

namespace App\Http\Controllers;

use App\Models\category_model;
use App\Models\post_model;

class PublicController extends Controller
{
    public function home()
    {
        $categories = category_model::withCount(['posts' => fn ($q) => $q->where('status', 'publicado')])
            ->orderBy('name')
            ->get();

        $latest = post_model::with(['category', 'authors'])
            ->where('status', 'publicado')
            ->orderByDesc('activity_date')
            ->take(3)
            ->get();

        return view('public.home', compact('categories', 'latest'));
    }
}