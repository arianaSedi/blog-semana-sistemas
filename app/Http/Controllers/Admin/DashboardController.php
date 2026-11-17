<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\category_model;
use App\Models\post_model;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'Entradas' => post_model::count(),
            'Publicadas' => post_model::where('status', 'publicado')->count(),
            'Borradores' => post_model::where('status', 'borrador')->count(),
            'Categorías' => category_model::count(),
        ];

        $latest = post_model::with('category')->latest()->take(5)->get();
        $categories = category_model::withCount('posts')->orderBy('name')->get();

        return view('admin.dashboard', compact('stats', 'latest', 'categories'));
    }
}