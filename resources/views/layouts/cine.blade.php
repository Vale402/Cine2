<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CineApp — Tu experiencia de cine, digital y sin filas.">
    <title>CineApp — @yield('titulo', 'Bienvenido')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CineApp Design System --}}
    <link href="{{ asset('css/cine.css') }}" rel="stylesheet">
    @yield('estilos')
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-cine">
        <a class="navbar-brand" href="{{ route('cartelera') }}">
            🎬 CINEAPP
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false" aria-label="Menú">
            <i class="bi bi-list text-white fs-4"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cartelera') ? 'active' : '' }}"
                       href="{{ route('cartelera') }}">
                        <i class="bi bi-film"></i> Cartelera
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('mis.boletos') ? 'active' : '' }}"
                       href="{{ route('mis.boletos') }}">
                        <i class="bi bi-ticket-perforated"></i> Mis Boletos
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <span class="nav-link nav-user">
                        <i class="bi bi-person-circle"></i>
                        {{ Auth::user()->name }}
                    </span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    {{-- CONTENIDO --}}
    <main class="container py-4">
        {{-- Alertas --}}
        @if(session('error'))
            <div class="alert-cine alert-cine-error d-flex align-items-center gap-2 mb-4 animate-slide-down" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-cine-primary"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"
                        style="filter:invert(1);opacity:0.5;"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert-cine alert-cine-success d-flex align-items-center gap-2 mb-4 animate-slide-down" role="alert">
                <i class="bi bi-check-circle-fill text-cine-success"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"
                        style="filter:invert(1);opacity:0.5;"></button>
            </div>
        @endif

        @yield('contenido')
    </main>

    {{-- FOOTER --}}
    <footer class="footer-cine text-center">
        <p class="mb-0">
            <span class="footer-brand">🎬 CINEAPP</span>
            <span class="ms-2">&copy; {{ date('Y') }} — Todos los derechos reservados</span>
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>