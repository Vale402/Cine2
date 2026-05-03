<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CineApp — Registro</title>

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
                <p>Crea tu cuenta para comprar boletos</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label-cine">
                        <i class="bi bi-person"></i> Nombre completo
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           class="form-control form-control-cine" placeholder="Tu nombre"
                           required autofocus autocomplete="name">
                    @error('name')
                        <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label-cine">
                        <i class="bi bi-envelope"></i> Correo electrónico
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-control form-control-cine" placeholder="tu@correo.com"
                           required autocomplete="username">
                    @error('email')
                        <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div class="mb-3">
                    <label for="telefono" class="form-label-cine">
                        <i class="bi bi-telephone"></i> Teléfono <span class="text-cine-muted">(opcional)</span>
                    </label>
                    <input id="telefono" type="tel" name="telefono" value="{{ old('telefono') }}"
                           class="form-control form-control-cine" placeholder="229 123 4567"
                           autocomplete="tel">
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label-cine">
                        <i class="bi bi-lock"></i> Contraseña
                    </label>
                    <input id="password" type="password" name="password"
                           class="form-control form-control-cine" placeholder="Mínimo 8 caracteres"
                           required autocomplete="new-password">
                    @error('password')
                        <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label-cine">
                        <i class="bi bi-lock-fill"></i> Confirmar contraseña
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="form-control form-control-cine" placeholder="Repite tu contraseña"
                           required autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-cine w-100 py-2 mb-3" style="font-size:1rem;">
                    <i class="bi bi-person-plus"></i> Crear Cuenta
                </button>

                <p class="text-center text-cine-muted mb-0" style="font-size:0.85rem;">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" class="text-cine-primary fw-semibold">Inicia sesión</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
