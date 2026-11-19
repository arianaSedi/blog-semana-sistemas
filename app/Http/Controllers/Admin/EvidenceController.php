<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\evidence_model;
use App\Models\post_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EvidenceController extends Controller
{
    // Registra evidencia de asistencia de una integrante
    public function store(Request $request, post_model $post)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in(array_keys(evidence_model::TYPES))],
            'evidence_date' => ['required', 'date'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:8192'],
        ], [
            'user_id.required' => 'Indica a qué integrante pertenece la evidencia.',
            'type.required' => 'Selecciona el tipo de evidencia.',
            'evidence_date.required' => 'La fecha de la evidencia es obligatoria.',
            'description.required' => 'Describe brevemente la evidencia.',
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
            'file.required' => 'Adjunta el archivo de la evidencia.',
            'file.mimes' => 'La evidencia debe ser una imagen (JPG, PNG, WEBP) o un PDF.',
            'file.max' => 'El archivo no debe pesar más de 8 MB.',
        ]);

        $data['file'] = $request->file('file')->store('evidencias', 'public');
        $post->evidences()->create($data);

        return back()->with('success', 'Evidencia registrada correctamente.');
    }

    // Elimina evidencia
    public function destroy(evidence_model $evidence)
    {
        if ($evidence->file) {
            Storage::disk('public')->delete($evidence->file);
        }
        $evidence->delete();

        return back()->with('success', 'Evidencia eliminada correctamente.');
    }
}