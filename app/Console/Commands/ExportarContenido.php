<?php

namespace App\Console\Commands;

use App\Models\post_model;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ExportarContenido extends Command
{
    protected $signature = 'blog:exportar';

    protected $description = 'Guarda las entradas creadas en el panel (texto, fotos y evidencias) dentro del proyecto';

    public function handle(): int
    {
        $destino = database_path('seeders/contenido');

        // Se vuelve a generar todo desde cero
        File::deleteDirectory($destino);
        File::ensureDirectoryExists($destino);

        $posts = post_model::with(['category', 'authors', 'images', 'evidences.user'])->orderBy('activity_date')->get();

        foreach ($posts as $post) {
            $carpeta = "{$destino}/{$post->slug}";
            File::ensureDirectoryExists($carpeta);

            $datos = [
                'titulo' => $post->title,
                'fecha' => $post->activity_date->format('Y-m-d'),
                'categoria' => $post->category->name,
                'categoria_descripcion' => $post->category->description,
                'resumen' => $post->excerpt,
                'contenido' => $post->content,
                'estado' => $post->status,
                'autoras' => $post->authors->pluck('email')->all(),
                'portada' => $this->copiar($post->cover_image, $carpeta),
                'galeria' => $post->images->map(fn ($img) => [
                    'archivo' => $this->copiar($img->path, $carpeta),
                    'descripcion' => $img->caption,
                ])->all(),
                'evidencias' => $post->evidences->map(fn ($ev) => [
                    'archivo' => $this->copiar($ev->file, $carpeta),
                    'autora' => $ev->user->email,
                    'tipo' => $ev->type,
                    'descripcion' => $ev->description,
                    'fecha' => $ev->evidence_date->format('Y-m-d'),
                ])->all(),
            ];

            File::put("{$carpeta}/entrada.json", json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->line("Exportada: {$post->title}");
        }

        $this->info("Listo: {$posts->count()} entradas guardadas en database/seeders/contenido");

        return self::SUCCESS;
    }

    // Copia un archivo de storage a la carpeta de la entrada y devuelve su nombre
    private function copiar(?string $ruta, string $carpeta): ?string
    {
        if (! $ruta || ! Storage::disk('public')->exists($ruta)) {
            return null;
        }

        $nombre = basename($ruta);
        File::copy(Storage::disk('public')->path($ruta), "{$carpeta}/{$nombre}");

        return $nombre;
    }
}