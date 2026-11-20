<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') · Blog SDS26</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&family=montserrat:700,800,900&display=swap" rel="stylesheet">
    @vite(['resources/sass/blog.scss', 'resources/js/blog.js'])
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-marino-oscuro border-bottom border-4 border-primary">
    <div class="container">
        <a class="navbar-brand font-display fw-bold" href="{{ route('dashboard') }}">
            SDS26 <span class="text-lima">Green IoT</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuAdmin"
                aria-controls="menuAdmin" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuAdmin">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">Panel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.posts.index') }}">Entradas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.categories.index') }}">Categorías</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-lg-3">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('home') }}">Ver blog</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>
</body>
</html>