@extends('layouts.public')

@section('title', $category->name)

@section('content')

<section class="bg-marino text-white py-5">
    <div class="container py-3">

        <a href="{{ route('home') }}#actividades"
           class="btn btn-outline-light btn-sm mb-4">
            &larr; Volver a actividades
        </a>

        <p class="text-lima text-uppercase fw-bold small">
            Semana de Sistemas 2026
        </p>

        <h1 class="display-5 font-display fw-bold text-uppercase">
            {{ $category->name }}
        </h1>

        <div class="subrayado-lima mt-3 mb-3"></div>

        <p class="lead text-white-50 mb-0">
            {{ $category->description }}
        </p>

    </div>
</section>

<section class="py-5">
    <div class="container">

        <h2 class="font-display text-uppercase mb-2">
            Publicaciones
        </h2>

        <p class="text-muted mb-4">
            {{ $posts->total() }} publicaciones disponibles
        </p>

        <div class="row g-4">

            @forelse ($posts as $post)

                <div class="col-md-6 col-lg-4">

                    <div class="card h-100 border-0 shadow-sm">

                        @if ($post->cover_image)
                            <a href="{{ route('public.post', $post->slug) }}">
                                <img
                                    src="{{ asset('storage/'.$post->cover_image) }}"
                                    class="card-img-top"
                                    alt="{{ $post->title }}"
                                    style="aspect-ratio:16/9; object-fit:cover;"
                                >
                            </a>
                        @endif

                        <div class="card-body d-flex flex-column">

                            <p class="text-success small fw-bold text-uppercase">
                                {{ $category->name }}
                            </p>

                            <h3 class="h5 font-display">
                                {{ $post->title }}
                            </h3>

                            <p class="small text-muted">
                                {{ $post->activity_date->format('d/m/Y') }}
                            </p>

                            <p class="small">
                                {{ $post->summary() }}
                            </p>

                            <div class="mt-auto">
                                <a href="{{ route('public.post', $post->slug) }}"
                                   class="btn btn-primary btn-sm">
                                    Leer publicación &rarr;
                                </a>
                            </div>

                        </div>

                        <div class="card-footer bg-white text-muted small">
                            Por {{ $post->authors->pluck('name')->join(' y ') }}
                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">
                    <div class="bg-white border rounded-3 p-5 text-center">
                        <h3 class="h5">Todavía no hay publicaciones</h3>

                        <p class="text-muted mb-0">
                            Próximamente compartiremos nuevas experiencias.
                        </p>
                    </div>
                </div>

            @endforelse

        </div>

        <div class="mt-5">
            {{ $posts->links() }}
        </div>

    </div>
</section>

@endsection
