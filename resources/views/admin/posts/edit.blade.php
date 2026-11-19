@extends('layouts.admin')

@section('title', 'Editar entrada')

@section('content')
<h1 class="h3 font-display text-uppercase mb-1">Editar entrada</h1>
<p class="text-muted mb-4">{{ $post->title }}</p>

<form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    @include('admin.posts._form')
</form>

<div class="card border-0 shadow-sm mt-5">
    <div class="card-header bg-white fw-bold font-display text-uppercase">Galería ({{ $post->images->count() }})</div>
    <div class="card-body">
        @if ($post->images->isEmpty())
            <p class="text-muted mb-0">Esta entrada todavía no tiene imágenes en la galería. Agrégalas desde el formulario de arriba.</p>
        @else
            <div class="row g-3">
                @foreach ($post->images as $image)
                    <div class="col-sm-6 col-lg-3">
                        <div class="border rounded-3 overflow-hidden h-100">
                            <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $image->caption }}" class="w-100"
                                 style="aspect-ratio:4/3; object-fit:cover">
                            <div class="p-2">
                                <form action="{{ route('admin.images.update', $image) }}" method="POST" class="input-group input-group-sm mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="caption" value="{{ $image->caption }}" maxlength="200"
                                           class="form-control" placeholder="Descripción">
                                    <button class="btn btn-outline-secondary">Guardar</button>
                                </form>
                                <form action="{{ route('admin.images.destroy', $image) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta imagen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-link text-danger p-0">Eliminar imagen</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white fw-bold font-display text-uppercase">Evidencias de asistencia</div>
    <div class="card-body">
        <p class="text-muted small">Cada integrante debe comprobar su asistencia a la actividad con una prueba propia.</p>

        <form action="{{ route('admin.evidences.store', $post) }}" method="POST" enctype="multipart/form-data"
              class="row g-2 bg-light rounded-3 p-3 mb-4" novalidate>
            @csrf
            <div class="col-md-4 col-lg-2">
                <label class="form-label small fw-semibold">Integrante *</label>
                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id', auth()->id()) == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-lg-2">
                <label class="form-label small fw-semibold">Tipo *</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror">
                    @foreach ($evidenceTypes as $valor => $texto)
                        <option value="{{ $valor }}" @selected(old('type') === $valor)>{{ $texto }}</option>
                    @endforeach
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-lg-2">
                <label class="form-label small fw-semibold">Fecha *</label>
                <input type="date" name="evidence_date"
                       value="{{ old('evidence_date', $post->activity_date->format('Y-m-d')) }}"
                       class="form-control @error('evidence_date') is-invalid @enderror">
                @error('evidence_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 col-lg-3">
                <label class="form-label small fw-semibold">Archivo (imagen o PDF) *</label>
                <input type="file" name="file" accept="image/*,application/pdf"
                       class="form-control @error('file') is-invalid @enderror">
                @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 col-lg-3">
                <label class="form-label small fw-semibold">Descripción *</label>
                <input type="text" name="description" value="{{ old('description') }}" maxlength="1000"
                       class="form-control @error('description') is-invalid @enderror" placeholder="Ej.: Foto en el taller, mesa 3">
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary">Registrar evidencia</button>
            </div>
        </form>

        <div class="row g-3">
            @forelse ($post->evidences as $evidence)
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex gap-3 border rounded-3 p-3 h-100">
                        @if ($evidence->isImage())
                            <a href="{{ asset('storage/'.$evidence->file) }}" target="_blank">
                                <img src="{{ asset('storage/'.$evidence->file) }}" alt="" class="rounded"
                                     style="width:72px; height:72px; object-fit:cover">
                            </a>
                        @else
                            <a href="{{ asset('storage/'.$evidence->file) }}" target="_blank"
                               class="d-flex align-items-center justify-content-center rounded bg-marino text-white fw-bold small text-decoration-none"
                               style="width:72px; height:72px">PDF</a>
                        @endif
                        <div class="small">
                            <div class="fw-semibold">{{ $evidence->user->name }}</div>
                            <div class="text-muted">
                                {{ \App\Models\evidence_model::TYPES[$evidence->type] ?? $evidence->type }} · {{ $evidence->evidence_date->format('d/m/Y') }}
                            </div>
                            <div>{{ $evidence->description }}</div>
                            <form action="{{ route('admin.evidences.destroy', $evidence) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta evidencia?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-link btn-sm text-danger p-0">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">Todavía no hay evidencias registradas para esta entrada.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection