<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · Blog SDS26</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/sass/blog.scss', 'resources/js/blog.js'])
</head>
<body>
@php
    $fotoLogin = collect(glob(public_path('img/portada/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG}'), GLOB_BRACE))->sort()->first();
@endphp

<div class="login-pantalla">

    <aside class="login-foto d-none d-lg-flex"
           @if ($fotoLogin) style="background-image: url('{{ asset('img/portada/'.basename($fotoLogin)) }}')" @endif>
        <div class="login-foto-contenido">
            <a href="{{ route('home') }}" class="d-flex align-items-center gap-3 text-decoration-none">
                <img src="{{ asset('img/logo-sds26.png') }}" alt="" style="height: 56px" translate="no">
                <span class="fs-4 fw-bold text-white">SDS26 <span class="text-lima">Green IoT</span></span>
            </a>

            <div>
                <p class="text-lima fw-bold small text-uppercase mb-2" style="letter-spacing:.15em">Semana de Sistemas 2026 · ASEIS · FMO</p>
                <p class="fs-3 fw-bold text-white lh-sm mb-2">Lo que vivimos en cada actividad,<br>contado por nosotras.</p>
                <p class="text-white-50 mb-0">Panel para publicar entradas y registrar evidencias.</p>
            </div>
        </div>
    </aside>

    <main class="login-formulario">
        <div class="login-caja">

            <a href="{{ route('home') }}" class="enlace-volver volver-esquina">Volver al blog</a>

            <div class="d-flex d-lg-none align-items-center gap-2 mb-4">
                <img src="{{ asset('img/logo-sds26.png') }}" alt="" style="height: 44px" translate="no">
                <span class="fw-bold fs-5">SDS26 <span class="text-lima-oscuro">Green IoT</span></span>
            </div>

            <h1 class="h2 fw-bold mb-1">Iniciar sesión</h1>
            <p class="text-muted mb-4">Acceso para personal administrativo del blog</p>

            @if (session('success'))
                <div class="alert alert-success py-2 small">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                           placeholder="nombre@blog.com" required autofocus autocomplete="username">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <div class="campo-clave">
                        <input id="password" type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required autocomplete="current-password">
                                <button type="button" id="verClave" class="btn-ver-clave" aria-label="Mostrar contraseña">
                            <svg id="ojoAbierto" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="ojoCerrado" class="d-none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3l18 18"/>
                                <path d="M10.6 5.1A10.4 10.4 0 0 1 12 5c6.5 0 10 7 10 7a17.6 17.6 0 0 1-3.2 4.2M6.6 6.6C3.9 8.3 2 12 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/>
                                <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Mantener la sesión iniciada</label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">Entrar al panel</button>
            </form>
        </div>
    </main>
</div>

</body>
</html>