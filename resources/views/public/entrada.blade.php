@extends('layouts.public')

@section('title', $post->title)

@section('content')

<section class="bg-marino text-white py-5">
    <div class="container py-3">

        <a href="{{ route('public.category', $post->category->slug) }}"
           class="btn btn-outline-light btn-sm mb-4">
            Volver a {{ $post->category->name }}
        </a>

        <p class="text-lima fw-bold text-uppercase small">
            {{ $post->category->name }} | Semana de Sistemas 2026
        </p>

        <h1 class="display-5 font-display fw-bold">
            {{ $post->title }}
        </h1>

        <div class="subrayado-lima mt-3 mb-4"></div>

        <div class="d-flex flex-wrap gap-4 text-white-50">

            <span>
                Fecha:
                {{ $post->activity_date->translatedFormat('j \d\e F \d\e Y') }}
            </span>

            <span>
                Por {{ $post->authors->pluck('name')->join(' y ') }}
            </span>

        </div>

    </div>
</section>

<section class="py-5">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">

                <article class="bg-white rounded-4 shadow-sm p-3 p-md-5">
                    @if ($post->cover_image)
                        <img
                            src="{{ asset('storage/'.$post->cover_image) }}"
                            alt="{{ $post->title }}"
                            class="img-fluid rounded-3 w-100 mb-4"
                            style="max-height:550px; object-fit:contain;">

                    @endif

                    @if ($post->excerpt)

                        <div class="border-start border-4 border-primary ps-3 mb-4">
                            <p class="lead fw-semibold mb-0">
                                {{ $post->excerpt }}
                            </p>
                        </div>

                    @endif
                    <h2 class="h4 font-display mb-3">
                        Sobre la actividad
                    </h2>

                    <div style="white-space:pre-wrap; line-height:1.9; overflow-wrap:anywhere;">{{ $post->content }}</div>

            
                    @if ($post->images->isNotEmpty())
                        <hr class="my-5">

                        <h2 class="font-display text-uppercase">
                            Galería de fotografías
                        </h2>

                        <div class="subrayado-lima mt-2 mb-4"></div>

                        <div class="row g-4">

                            @foreach ($post->images as $image)

                                <div class="col-md-6 col-lg-4">
                                    <figure class="h-100">

                                        <a href="{{ asset('storage/'.$image->path) }}"
                                            target="_blank"
                                            rel="noopener noreferrer">
                                            <img
                                                src="{{ asset('storage/'.$image->path) }}"
                                                alt="{{ $image->caption ?: $post->title }}"
                                                class="img-fluid rounded-3 shadow-sm w-100"
                                                style="aspect-ratio:4/3; object-fit:cover;"
                                                loading="lazy">
                                        </a>

                                        @if ($image->caption)

                                            <figcaption class="small text-muted mt-2">
                                                {{ $image->caption }}
                                            </figcaption>

                                        @endif

                                    </figure>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="mt-5">
                        <a href="{{ route('public.category', $post->category->slug) }}"
                           class="btn btn-primary">
                            Ver más publicaciones
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

@endsection
