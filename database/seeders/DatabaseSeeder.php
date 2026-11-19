<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Credenciales de prueba
        User::updateOrCreate(
            ['email' => 'ari@blog.com'],
            ['name' => 'Ariana', 'password' => 'Semana.2026']
        );

        User::updateOrCreate(
            ['email' => 'ange@blog.com'],
            ['name' => 'Angelica', 'password' => 'Semana.2026']
        );

        // Las 7 actividades
        $this->call(CategorySeeder::class);

        // Entradas exportadas desde el panel (texto, fotos y evidencias)
        $this->call(PostSeeder::class);

        // Crea public/storage para que las fotos se vean (si no existe)
        if (! is_link(public_path('storage'))) {
            Artisan::call('storage:link');
        }
    }
}