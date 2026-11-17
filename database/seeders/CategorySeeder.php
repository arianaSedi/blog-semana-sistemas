<?php

namespace Database\Seeders;

use App\Models\category_model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Ponencias' => 'Tema, expositor, ideas centrales y aprendizajes.',
            'Talleres' => 'Herramientas, procedimiento, práctica y resultados.',
            'Bingo' => 'Dinámica, experiencia y participación.',
            'Feria de proyectos' => 'Proyectos visitados, tecnologías e innovación.',
            'Comelona de pizza' => 'Desarrollo, convivencia y experiencia.',
            'Torneo de fútbol sala' => 'Desarrollo, equipos y participación como jugador o espectador.',
            'Parrillada' => 'Descripción, convivencia y reflexión.',
        ];

        foreach ($categorias as $nombre => $descripcion) {
            category_model::updateOrCreate(
                ['slug' => Str::slug($nombre)],
                ['name' => $nombre, 'description' => $descripcion]
            );
        }
    }
}