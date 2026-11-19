<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\post_image_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostImageController extends Controller
{
    // Guardar la descripcion de una imagen
    public function update(Request $request, post_image_model $image)
    {
        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:200'],
        ], [
            'caption.max' => 'La descripción de la imagen no debe superar 200 caracteres.',
        ]);

        $image->update($data);

        return back()->with('success', 'Descripción de la imagen guardada.');
    }

    // Eliminar una imagen
    public function destroy(post_image_model $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }
}