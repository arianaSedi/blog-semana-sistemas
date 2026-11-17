<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesion | Blog SDS26</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&family=montserrat:700,800,900&display=swap" rel="stylesheet">
    @vite(['resources/sass/blog.scss', 'resources/js/blog.js'])
</head>
<body class="bg-marino min-vh-100 d-flex align-items-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">

            <div class="text-center text-white mb-4">
                <h1 class="font-display fw-bolder mb-0">SDS26 <span class="text-lima">GREEN IOT</span></h1>
                <p class="text-uppercase small fw-bold text-lima mt-2 mb-0" style="letter-spacing:.2em">
                    Innovar sin dejar huella de carbono
                </p>
            </div>

            <div class="card border-0 border-top border-5 border-primary shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <h2 class="h4 font-display mb-4">Iniciar sesión</h2>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   required autofocus autocomplete="username">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <input id="password" type="password" name="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   required autocomplete="current-password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 text-uppercase">Entrar</button>
                    </form>
                </div>
            </div>

            <p class="text-center mt-3 mb-0">
                <a href="{{ route('home') }}" class="text-white-50">← Volver al blog</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>