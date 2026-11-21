@extends('layouts.admin')

@section('title', 'Editar entrada')

@section('content')

<div class="cabecera-entrada pb-4 mb-4">
    <div class="d-flex justify-content-between align-items-start gap-3">
        <a href="{{ route('admin.posts.index') }}" class="enlace-volver">Volver a entradas</a>
        @if ($post->isPublished())
            <a href="{{ route('public.post', $post->slug) }}" target="_blank" class="btn btn-outline-light btn-sm">Ver en el blog</a>
        @endif
    </div>

    <p class="eyebrow text-lima mt-2 mb-1">Editar entrada</p>
    <h1 class="h3 fw-bold text-white mb-2">{{ $post->title }}</h1>
    <div class="d-flex flex-wrap align-items-center gap-3 small">
        <span class="estado-pill {{ $post->status }}">{{ \App\Models\post_model::STATUSES[$post->status] }}</span>
        <span>{{ $post->category->name }}</span>
        <span>{{ $post->activity_date->format('d/m/Y') }}</span>
    </div>
</div>

<form id="contenido" action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    @include('admin.posts._form')
</form>

<section id="galeria" class="tarjeta p-4 mt-4">
    <div class="encabezado-seccion d-flex flex-wrap justify-content-between align-items-end gap-2">
        <div>
            <h2 class="h5 fw-bold mb-1">Galería</h2>
            <p class="small text-muted mb-0">Escribe una descripción breve para cada foto; se muestra en el blog.</p>
        </div>
        <span class="small fw-semibold text-muted">{{ $post->images->count() }} {{ $post->images->count() === 1 ? 'foto' : 'fotos' }}</span>
    </div>

    @if ($post->images->isEmpty())
        <div class="caja-vacia">Esta entrada todavía no tiene fotos en la galería. Agrégalas en la sección <strong>Imágenes</strong> y guarda.</div>
    @else
        <div class="row g-3">
            @foreach ($post->images as $image)
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="foto-admin">
                        <div class="position-relative">
                            <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $image->caption }}">
                            <form action="{{ route('admin.images.destroy', $image) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta imagen?')" class="quitar-foto">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm">Quitar</button>
                            </form>
                        </div>
                        <form action="{{ route('admin.images.update', $image) }}" method="POST" class="d-flex gap-2 p-2">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="caption" value="{{ $image->caption }}" maxlength="200"
                                   class="form-control form-control-sm" placeholder="Descripción">
                            <button class="btn btn-sm btn-outline-secondary">Guardar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>

<section id="evidencias" class="tarjeta p-4 mt-4">
    <div class="encabezado-seccion">
        <h2 class="h5 fw-bold mb-1">Evidencias de asistencia</h2>
        <p class="small text-muted mb-0">Cada integrante comprueba por separado su asistencia a la actividad con una prueba propia.</p>
    </div>

    <div class="row g-4">
        @foreach ($users as $user)
            @php
                $suyas = $post->evidences->where('user_id', $user->id);
                $esEsta = old('user_id') == $user->id; // para mostrar errores solo en su columna
            @endphp
            <div class="col-lg-6">
                <div class="columna-integrante h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 fw-bold mb-0">{{ $user->name }}</h3>
                        <span class="conteo {{ $suyas->count() ? 'ok' : 'falta' }}">{{ $suyas->count() }}</span>
                    </div>

                    @forelse ($suyas as $evidence)
                        <div class="fila-evidencia">
                            @if ($evidence->isImage())
                                <a href="{{ asset('storage/'.$evidence->file) }}" target="_blank" class="flex-shrink-0">
                                    <img src="{{ asset('storage/'.$evidence->file) }}" alt="Evidencia de {{ $user->name }}">
                                </a>
                            @else
                                <a href="{{ asset('storage/'.$evidence->file) }}" target="_blank" class="miniatura-pdf flex-shrink-0">PDF</a>
                            @endif
                            <div class="flex-grow-1 min-w-0 small">
                                <div class="fw-semibold">{{ \App\Models\evidence_model::TYPES[$evidence->type] ?? $evidence->type }}</div>
                                <div class="text-muted">{{ $evidence->evidence_date->format('d/m/Y') }}</div>
                                <div class="text-truncate">{{ $evidence->description }}</div>
                            </div>
                            <form action="{{ route('admin.evidences.destroy', $evidence) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta evidencia?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-link btn-sm text-danger p-0">Eliminar</button>
                            </form>
                        </div>
                    @empty
                        <div class="caja-vacia mb-3">Aún no registra evidencia para esta actividad.</div>
                    @endforelse

                    {{-- Registrar evidencia de esta integrante --}}
                    <form action="{{ route('admin.evidences.store', $post) }}" method="POST" enctype="multipart/form-data"
                          class="form-evidencia" novalidate>
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <p class="small fw-bold mb-2">Registrar evidencia</p>

                        <div class="row g-2">
                            <div class="col-sm-6">
                                <label class="form-label small mb-1">Tipo *</label>
                                <select name="type" class="form-select form-select-sm {{ $esEsta && $errors->has('type') ? 'is-invalid' : '' }}">
                                    @foreach ($evidenceTypes as $valor => $texto)
                                        <option value="{{ $valor }}" @selected($esEsta && old('type') === $valor)>{{ $texto }}</option>
                                    @endforeach
                                </select>
                                @if ($esEsta) @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small mb-1">Fecha *</label>
                                <input type="date" name="evidence_date"
                                       value="{{ $esEsta ? old('evidence_date') : $post->activity_date->format('Y-m-d') }}"
                                       class="form-control form-control-sm {{ $esEsta && $errors->has('evidence_date') ? 'is-invalid' : '' }}">
                                @if ($esEsta) @error('evidence_date') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label small mb-1">Archivo (imagen o PDF) *</label>
                                <input type="file" name="file" accept="image/*,application/pdf"
                                       class="form-control form-control-sm {{ $esEsta && $errors->has('file') ? 'is-invalid' : '' }}">
                                @if ($esEsta) @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label small mb-1">Descripción *</label>
                                <input type="text" name="description" maxlength="1000"
                                       value="{{ $esEsta ? old('description') : '' }}"
                                       class="form-control form-control-sm {{ $esEsta && $errors->has('description') ? 'is-invalid' : '' }}"
                                       placeholder="Ej.: Foto en el taller, mesa 3">
                                @if ($esEsta) @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-primary btn-sm">Registrar evidencia</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection