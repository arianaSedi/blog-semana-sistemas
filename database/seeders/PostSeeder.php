<?php

namespace Database\Seeders;

use App\Models\category_model;
use App\Models\post_model;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    // Carga las entradas guardadas con php artisan blog:exportar
    public function run(): void
    {
        $origen = database_path('seeders/contenido');

        if (! File::isDirectory($origen)) {
            $this->command->warn('No hay contenido exportado todavía (database/seeders/contenido).');
            return;
        }

        foreach (File::directories($origen) as $carpeta) {
            $e = json_decode(File::get("{$carpeta}/entrada.json"), true);
            $slug = basename($carpeta);

            $categoria = category_model::firstOrCreate(
                ['slug' => Str::slug($e['categoria'])],
                ['name' => $e['categoria'], 'description' => $e['categoria_descripcion'] ?? null]
            );

            $autoras = User::whereIn('email', $e['autoras'])->pluck('id')->all();

            $post = post_model::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $autoras[0] ?? User::first()->id,
                    'category_id' => $categoria->id,
                    'title' => $e['titulo'],
                    'activity_date' => $e['fecha'],
                    'excerpt' => $e['resumen'],
                    'content' => $e['contenido'],
                    'status' => $e['estado'],
                    'cover_image' => $this->copiar($carpeta, $e['portada'], $slug),
                ]
            );

            $post->authors()->sync($autoras);

            $post->images()->delete();
            foreach ($e['galeria'] as $img) {
                if ($ruta = $this->copiar($carpeta, $img['archivo'], $slug)) {
                    $post->images()->create(['path' => $ruta, 'caption' => $img['descripcion']]);
                }
            }

            $post->evidences()->delete();
            foreach ($e['evidencias'] as $ev) {
                $autora = User::where('email', $ev['autora'])->first();
                $ruta = $this->copiar($carpeta, $ev['archivo'], $slug);

                if ($autora && $ruta) {
                    $post->evidences()->create([
                        'user_id' => $autora->id,
                        'type' => $ev['tipo'],
                        'file' => $ruta,
                        'description' => $ev['descripcion'],
                        'evidence_date' => $ev['fecha'],
                    ]);
                }
            }
        }
    }

    // Copia una foto del proyecto a storage/app/public y devuelve su ruta
    private function copiar(string $carpeta, ?string $archivo, string $slug): ?string
    {
        if (! $archivo || ! File::exists("{$carpeta}/{$archivo}")) {
            return null;
        }

        $ruta = "entradas/{$slug}/{$archivo}";
        Storage::disk('public')->put($ruta, File::get("{$carpeta}/{$archivo}"));

        return $ruta;
    }
}