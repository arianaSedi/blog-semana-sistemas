@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 font-display text-uppercase mb-0">Categorías</h1>
        <small class="text-muted">Actividades de la Semana de Sistemas 2026</small>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Nueva categoría</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th class="d-none d-md-table-cell">Contenido mínimo / descripción</th>
                    <th class="text-center">Entradas</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td class="d-none d-md-table-cell text-muted small">{{ $category->description }}</td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-secondary">{{ $category->posts_count }}</span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-secondary">Editar</a>

                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar la categoría {{ $category->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        @disabled($category->posts_count > 0)
                                        title="{{ $category->posts_count > 0 ? 'Tiene entradas asociadas' : 'Eliminar' }}">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No hay categorías registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection