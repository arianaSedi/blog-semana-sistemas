@extends('layouts.admin')

@section('title', 'Entradas')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 font-display text-uppercase mb-0">Entradas</h1>
        <small class="text-muted">Publicaciones del blog por actividad</small>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ Nueva entrada</a>
</div>

<form method="GET" class="card border-0 shadow-sm mb-4">
    <div class="card-body row g-2">
        <div class="col-md-5">
            <input type="search" name="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por título">
        </div>
        <div class="col-md-3">
            <select name="categoria" class="form-select">
                <option value="">Todas las categorías</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('categoria') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="estado" class="form-select">
                <option value="">Todos</option>
                @foreach (\App\Models\post_model::STATUSES as $valor => $texto)
                    <option value="{{ $valor }}" @selected(request('estado') === $valor)>{{ $texto }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-secondary">Filtrar</button>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Entrada</th>
                    <th class="d-none d-md-table-cell">Categoría</th>
                    <th class="d-none d-lg-table-cell">Fecha</th>
                    <th class="d-none d-lg-table-cell">Autoras</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if ($post->cover_image)
                                    <img src="{{ asset('storage/'.$post->cover_image) }}" alt="" class="rounded"
                                         style="width:64px; height:48px; object-fit:cover">
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $post->title }}</div>
                                    <small class="text-muted">{{ $post->images_count }} imágenes · {{ $post->evidences_count }} evidencias</small>
                                </div>
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $post->category->name }}</td>
                        <td class="d-none d-lg-table-cell text-nowrap">{{ $post->activity_date->format('d/m/Y') }}</td>
                        <td class="d-none d-lg-table-cell">{{ $post->authors->pluck('name')->join(', ') }}</td>
                        <td>
                            <span class="badge {{ $post->isPublished() ? 'bg-primary' : 'bg-secondary' }}">
                                {{ \App\Models\post_model::STATUSES[$post->status] }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-secondary">Editar</a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline"
                                  data-titulo="¿Eliminar «{{ $post->title }}»?"
                                  data-confirmar="También se borrarán sus fotos y evidencias. Esta acción no se puede deshacer.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No hay entradas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $posts->links() }}</div>
@endsection