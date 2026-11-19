@extends('layouts.public')

@section('title', 'Inicio')

@section('content')
@php
    // Foto de portada opcional: public/img/portada.jpg
    $portada = file_exists(public_path('img/portada.jpg')) ? asset('img/portada.jpg') : null;
@endphp

<section class="bg-marino text-white py-5">
    <div class="container py-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <p class="text-uppercase fw-bold text-lima small mb-2" style="letter-spacing:.2em">
                    Semana de Sistemas 2026 · Green IoT
                </p>
                <h1 class="display-4 font-display fw-bolder text-uppercase mb-3">
                    Innovar sin dejar <span class="text-lima">huella de carbono</span>
                </h1>
                <p class="lead text-white-50 mb-4">
                    En este blog contamos cómo vivimos cada actividad de la Semana de Sistemas 2026
                    en la Facultad Multidisciplinaria Oriental: lo que aprendimos, lo que compartimos
                    y nuestras evidencias de participación.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#actividades" class="btn btn-primary btn-lg px-4">Ver actividades</a>
                    <a href="#semana" class="btn btn-outline-light btn-lg px-4">Sobre la semana</a>
                </div>
            </div>

            <div class="col-lg-5">
                @if ($portada)
                    <img src="{{ $portada }}" alt="Semana de Sistemas 2026" class="img-fluid rounded-4 shadow-lg"
                         style="aspect-ratio:4/3; object-fit:cover; width:100%">
                @else
                    <div class="border border-2 border-primary rounded-4 p-5 text-center">
                        <p class="font-display display-3 fw-bolder mb-0">SDS<span class="text-lima">26</span></p>
                        <p class="text-lima fw-bold text-uppercase mb-3" style="letter-spacing:.3em">Green IoT</p>
                        <hr class="border-primary opacity-100 w-25 mx-auto">
                        <p class="small text-white-50 mb-0">Universidad de El Salvador<br>Facultad Multidisciplinaria Oriental</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section id="semana" class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <h2 class="font-display text-uppercase">La Semana de Sistemas 2026</h2>
                <div class="subrayado-lima mt-2"></div>
            </div>
            <div class="col-lg-8">
                <p>
                    La <strong>Semana de Sistemas 2026</strong> reúne a estudiantes y docentes de la carrera en la
                    Facultad Multidisciplinaria Oriental de la Universidad de El Salvador. Durante la semana hay
                    ponencias, talleres, feria de proyectos, bingo, comelona de pizza, torneo de fútbol sala y parrillada.
                </p>
                <p>
                    El tema de este año es <strong>Green IoT</strong>: usar el Internet de las Cosas para cuidar el
                    planeta, con dispositivos que consumen menos energía y tecnología que ayuda a monitorear el ambiente.
                </p>

                <div class="row g-3 mt-2">
                    @foreach ([
                        ['Conecta', 'Sensores y dispositivos que comparten datos útiles.'],
                        ['Ahorra', 'Menos consumo energético y más eficiencia.'],
                        ['Cuida', 'Tecnología pensada para el medio ambiente.'],
                    ] as [$titulo, $texto])
                        <div class="col-md-4">
                            <div class="h-100 border-start border-4 border-primary bg-white shadow-sm rounded-3 p-3">
                                <p class="font-display fw-bolder text-primary fs-4 mb-1">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                                <h3 class="h6 font-display text-uppercase mb-1">{{ $titulo }}</h3>
                                <p class="small text-muted mb-0">{{ $texto }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section id="actividades" class="py-5 bg-white">
    <div class="container">
        <h2 class="font-display text-uppercase">Actividades de la semana</h2>
        <div class="subrayado-lima mt-2 mb-4"></div>

        <div class="row g-3">
            @foreach ($categories as $category)
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 bg-marino text-white border-0 border-top border-4 border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <span class="font-display fw-bolder text-lima fs-3">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="small text-white-50">
                                    {{ $category->posts_count }} {{ $category->posts_count === 1 ? 'entrada' : 'entradas' }}
                                </span>
                            </div>
                            <h3 class="h5 font-display text-uppercase mt-2"> {{ $category->name }} </h3>

                            <p class="small text-white-50 mb-3"> {{ $category->description }} </p>

                            <a href="{{ route('public.category', $category->slug) }}" class="btn btn-primary btn-sm"> Ver publicaciones &rarr; </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="font-display text-uppercase">Últimas entradas</h2>
        <div class="subrayado-lima mt-2 mb-4"></div>

        <div class="row g-4">
            @forelse ($latest as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        @if ($post->cover_image)
                            <img src="{{ asset('storage/'.$post->cover_image) }}" class="card-img-top" alt="{{ $post->title }}"
                                 style="aspect-ratio:16/9; object-fit:cover">
                        @endif
                        <div class="card-body">
                            <p class="small text-uppercase fw-bold text-success mb-1">{{ $post->category->name }}</p>
                            <h3 class="h5 font-display">{{ $post->title }}</h3>
                            <p class="small text-muted mb-2">{{ $post->activity_date->translatedFormat('j \d\e F \d\e Y') }}</p>
                            <p class="small mb-3">{{ $post->summary() }}</p>

                            <a href="{{ route('public.post', $post->slug) }}" class="btn btn-primary btn-sm"> Leer publicación &rarr; </a>
                        </div>
                        <div class="card-footer bg-white small text-muted">
                            Por {{ $post->authors->pluck('name')->join(' y ') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="border rounded-3 bg-white text-center text-muted p-4">
                        Todavía no hay entradas publicadas. Pronto compartiremos nuestras experiencias de la semana.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section id="equipo" class="py-5 bg-white">
    <div class="container">
        <h2 class="font-display text-uppercase">Nuestro equipo</h2>
        <div class="subrayado-lima mt-2 mb-2"></div>
        <p class="text-muted mb-4">
            Estudiantes de Ingeniería de Sistemas Informáticos · Facultad Multidisciplinaria Oriental
        </p>

        <div class="row g-4 justify-content-center">
            @foreach ([
                ['Ariana Sedillo', 'SF22015', 'img/equipo/ariana.jpeg'],
                ['Angélica Amaya', 'AP22010', 'img/equipo/angelica.jpeg'],
            ] as [$nombre, $carnet, $foto])
                <div class="col-sm-6 col-lg-4">
                    <div class="h-100 bg-nube border-top border-4 border-primary rounded-3 p-4 text-center">
                        @if (file_exists(public_path($foto)))
                            <img src="{{ asset($foto) }}" alt="Foto de {{ $nombre }}"
                                 class="rounded-circle border border-4 border-white shadow-sm mb-3"
                                 style="width:130px; height:130px; object-fit:cover">
                        @else
                            <div class="rounded-circle bg-marino text-white d-inline-flex align-items-center justify-content-center font-display fw-bolder fs-1 border border-4 border-white shadow-sm mb-3"
                                 style="width:130px; height:130px">
                                {{ mb_substr($nombre, 0, 1) }}{{ mb_substr(explode(' ', $nombre)[1] ?? '', 0, 1) }}
                            </div>
                        @endif
                        <h3 class="h5 font-display mb-1">{{ $nombre }}</h3>
                        <p class="text-muted small mb-0">Carnet: {{ $carnet }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="text-center text-muted small mt-4 mb-0">
            Proyecto práctico de <strong>Técnicas de Programación para Internet</strong> · Universidad de El Salvador
        </p>
    </div>
</section>
@endsection