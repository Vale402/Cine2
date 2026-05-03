<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CineApp — Iniciar Sesión</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/cine.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card animate-scale-in">
            {{-- Logo --}}
            <div class="auth-logo">
                <h1>🎬 CINEAPP</h1>
                <p>Inicia sesión para continuar</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="alert-cine alert-cine-success mb-3" style="font-size:0.85rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label-cine">
                        <i class="bi bi-envelope"></i> Correo electrónico
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-control form-control-cine" placeholder="tu@correo.com"
                           required autofocus autocomplete="username">
                    @error('email')
                        <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label-cine">
                        <i class="bi bi-lock"></i> Contraseña
                    </label>
                    <input id="password" type="password" name="password"
                           class="form-control form-control-cine" placeholder="••••••••"
                           required autocomplete="current-password">
                    @error('password')
                        <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;">
                        <input type="checkbox" name="remember" id="remember_me"
                               style="accent-color: var(--cine-primary); width:16px; height:16px;">
                        <span class="text-cine-muted" style="font-size:0.85rem;">Recordarme</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-cine w-100 py-2 mb-3" style="font-size:1rem;">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                </button>

                {{-- Register link --}}
                <p class="text-center text-cine-muted mb-0" style="font-size:0.85rem;">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="text-cine-primary fw-semibold">Regístrate aquí</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
