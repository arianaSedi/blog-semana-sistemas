<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\category_model;
use App\Models\evidence_model;
use App\Models\post_image_model;
use App\Models\post_model;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Cifras generales
        $stats = [
            ['Entradas', post_model::count(), '#102444'],
            ['Publicadas', post_model::where('status', 'publicado')->count(), '#79BC26'],
            ['Borradores', post_model::where('status', 'borrador')->count(), '#E0A100'],
            ['Fotos', post_image_model::count() + post_model::whereNotNull('cover_image')->count(), '#1F3864'],
            ['Evidencias', evidence_model::count(), '#4E7F14'],
        ];

        $users = User::orderBy('name')->get();

        // Avance por actividad
        $avance = category_model::withCount(['posts as publicadas' => fn ($q) => $q->where('status', 'publicado'),'posts as borradores' => fn ($q) => $q->where('status', 'borrador'),
        ])
            ->orderBy('name')
            ->get()
            ->map(function ($categoria) use ($users) {
                $categoria->evidenciasPorUsuaria = $users->mapWithKeys(fn ($u) => [
                    $u->id => evidence_model::where('user_id', $u->id)
                        ->whereHas('post', fn ($q) => $q->where('category_id', $categoria->id))
                        ->count(),
                ]);

                // Completa = tiene entrada publicada y evidencia de todas las integrantes
                $categoria->completa = $categoria->publicadas > 0 && $categoria->evidenciasPorUsuaria->every(fn ($n) => $n > 0);
                return $categoria;
            });

        $completas = $avance->where('completa', true)->count();
        $porcentaje = $avance->count() ? round($completas * 100 / $avance->count()) : 0;

        $latest = post_model::with('category')->latest('updated_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'users', 'avance', 'completas', 'porcentaje', 'latest'));
    }
}