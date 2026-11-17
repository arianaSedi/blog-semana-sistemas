<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //CREDENCIALES DE PRUEBA
        User::updateOrCreate(
            ['email' => 'ari@blog.com'],
            ['name' => 'Ariana', 'password' => 'Semana.2026']
        );

        User::updateOrCreate(
            ['email' => 'ange@blog.com'],
            ['name' => 'Angelica', 'password' => 'Semana.2026']
        );

        // Carga las 7 actividades
        $this->call(CategorySeeder::class);
    }

}
