@extends('layouts.admin')

@section('title', 'Editar categoría')

@section('content')
<div class="cabecera-entrada pb-4 mb-4">
    <div class="d-flex justify-content-between align-items-start gap-3">
        <a href="{{ route('admin.categories.index') }}" class="enlace-volver">Volver a categorías</a>
        <a href="{{ route('public.category', $category->slug) }}" target="_blank" class="btn btn-outline-light btn-sm">Ver en el blog</a>
    </div>
    <p class="eyebrow text-lima mt-2 mb-1">Editar categoría</p>
    <h1 class="h3 fw-bold text-white mb-2">{{ $category->name }}</h1>
    <div class="small">
        {{ $category->posts->count() }} {{ $category->posts->count() === 1 ? 'entrada' : 'entradas' }}
        · {{ $category->posts->where('status', 'publicado')->count() }} publicadas
    </div>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-7">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            @include('admin.categories._form')
        </form>
    </div>

    <div class="col-lg-5">
        <div class="tarjeta p-4">
            <div class="encabezado-seccion d-flex justify-content-between align-items-center">
                <h2 class="h6 fw-bold mb-0">Entradas de esta categoría</h2>
                <a href="{{ route('admin.posts.create') }}" class="small fw-semibold">+ Nueva</a>
            </div>

            @forelse ($category->posts as $post)
                <a href="{{ route('admin.posts.edit', $post) }}" class="fila-cat-entrada">
                    @if ($post->cover_image)
                        <img src="{{ asset('storage/'.$post->cover_image) }}" alt="">
                    @else
                        <span class="sin-foto"></span>
                    @endif
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-truncate">{{ $post->title }}</div>
                        <small class="text-muted">{{ $post->activity_date->format('d/m/Y') }}</small>
                    </div>
                    <span class="estado-pill {{ $post->status }}">{{ \App\Models\post_model::STATUSES[$post->status] }}</span>
                </a>
            @empty
                <div class="caja-vacia">Todavía no hay entradas en esta categoría.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection