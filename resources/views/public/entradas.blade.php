@extends('layouts.public')

@section('title', 'Entradas')

@section('content')

<section class="bg-marino text-white py-5">
    <div class="container py-3">
        <p class="text-lima text-uppercase fw-bold small mb-2">Semana de Sistemas 2026</p>
        <h1 class="display-5 fw-bold mb-3">Todas las entradas</h1>
        <p class="lead text-white-50 mb-4">Las experiencias de cada actividad, de la más reciente a la más antigua.</p>

        <form action="{{ route('public.posts') }}" method="GET" class="buscador-entradas">
            @if ($categoriaSlug)
                <input type="hidden" name="categoria" value="{{ $categoriaSlug }}">
            @endif
            <input type="search" name="q" value="{{ $buscar }}" class="form-control form-control-lg"
                   placeholder="Buscar por título o contenido" aria-label="Buscar entradas">
            <button type="submit" class="btn btn-primary btn-lg px-4">Buscar</button>
        </form>
    </div>
</section>

<section class="py-5">
    <div class="container">

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('public.posts', array_filter(['q' => $buscar])) }}"
               class="chip text-decoration-none {{ $categoriaSlug ? '' : 'active' }}">Todas</a>
            @foreach ($categories as $category)
                <a href="{{ route('public.posts', array_filter(['q' => $buscar, 'categoria' => $category->slug])) }}"
                   class="chip text-decoration-none {{ $categoriaSlug === $category->slug ? 'active' : '' }}">
                    {{ $category->name }} <span class="opacity-75">({{ $category->posts_count }})</span>
                </a>
            @endforeach
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <p class="text-muted mb-0">
                {{ $posts->total() }} {{ $posts->total() === 1 ? 'entrada' : 'entradas' }}
                @if ($buscar) para «<span class="fw-semibold text-dark">{{ $buscar }}</span>» @endif
            </p>
            @if ($buscar || $categoriaSlug)
                <a href="{{ route('public.posts') }}" class="small fw-semibold">Quitar filtros</a>
            @endif
        </div>

        <div class="row g-4">
            @forelse ($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        @if ($post->cover_image)
                            <a href="{{ route('public.post', $post->slug) }}">
                                <img src="{{ asset('storage/'.$post->cover_image) }}" class="card-img-top" alt="{{ $post->title }}"
                                     style="aspect-ratio:16/9; object-fit:cover;">
                            </a>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <p class="small text-uppercase fw-bold text-lima-oscuro mb-1">{{ $post->category->name }}</p>
                            <h2 class="h5 fw-bold">{{ $post->title }}</h2>
                            <p class="small text-muted mb-2">{{ $post->activity_date->translatedFormat('j \d\e F \d\e Y') }}</p>
                            <p class="small mb-3">{{ $post->summary() }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('public.post', $post->slug) }}" class="btn btn-primary btn-sm">Leer publicación &rsaquo;</a>
                            </div>
                        </div>
                        <div class="card-footer bg-white small text-muted">
                            Por {{ $post->authors->pluck('name')->join(' y ') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="bg-white border rounded-3 p-5 text-center">
                        <h2 class="h5">No encontramos entradas</h2>
                        <p class="text-muted mb-3">Prueba con otra palabra o quita el filtro de categoría.</p>
                        <a href="{{ route('public.posts') }}" class="btn btn-outline-secondary btn-sm">Ver todas</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-5">
            {{ $posts->links('pagination::simple-bootstrap-5') }}
        </div>
    </div>
</section>
@endsection