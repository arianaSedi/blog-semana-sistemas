<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inicio') · Blog Semana de Sistemas 2026</title>
    <meta name="description" content="Blog de experiencias de la Semana de Sistemas 2026 · Green IoT · FMO, Universidad de El Salvador">
    <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/sass/blog.scss', 'resources/js/blog.js'])
</head>
<body class="d-flex flex-column min-vh-100" data-bs-spy="scroll" data-bs-target="#menuPublico" data-bs-root-margin="0px 0px -60%">

<nav class="navbar navbar-expand-lg navbar-dark bg-marino-oscuro border-bottom border-4 border-primary sticky-top">
    <div class="container">
        <a class="navbar-brand font-display fw-bold" href="{{ route('home') }}">
            SDS26 <span class="text-lima">Green IoT</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPublico"
                aria-controls="menuPublico" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPublico">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}#inicio">Inicio</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#actividades">Actividades</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.posts') ? 'active fw-bold' : '' }}" href="{{ route('public.posts') }}">Entradas</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#equipo">Equipo</a></li>
                <li class="nav-item ms-lg-2">
                    @auth
                        <a class="btn btn-primary btn-sm px-3" href="{{ route('dashboard') }}">Panel administrativo</a>
                    @else
                        <a class="btn btn-primary btn-sm px-3" href="{{ route('login') }}">Iniciar sesión</a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1">@yield('content')</main>

<footer class="bg-marino-oscuro text-white-50 pt-5 pb-3">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <p class="font-display fw-bold text-white fs-5 mb-1">SDS26 <span class="text-lima">Green IoT</span></p>
                <p class="mb-0">Innovar sin dejar huella de carbono.</p>
            </div>
            <div class="col-md-4">
                <p class="fw-bold text-white text-uppercase small mb-1">Semana de Sistemas 2026</p>
                <p class="mb-0">Universidad de El Salvador<br>Facultad Multidisciplinaria Oriental</p>
            </div>
            <div class="col-md-4">
                <p class="fw-bold text-white text-uppercase small mb-1">Proyecto</p>
                <p class="mb-0">Técnicas de Programación para Internet<br>Ariana Sedillo y Angélica Amaya</p>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <p class="text-center small mb-0">© {{ date('Y') }} Blog Semana de Sistemas | Laravel 12 + PostgreSQL</p>
    </div>
</footer>
</body>
</html>