<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineApp - @yield('titulo', 'Bienvenido')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0f0f;
            color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar-cine {
            background-color: #1a1a1a;
            border-bottom: 2px solid #e50914;
        }
        .navbar-brand {
            color: #e50914 !important;
            font-weight: bold;
            font-size: 1.5rem;
            letter-spacing: 2px;
        }
        .nav-link {
            color: #ffffff !important;
        }
        .nav-link:hover {
            color: #e50914 !important;
        }
        .btn-cine {
            background-color: #e50914;
            color: white;
            border: none;
        }
        .btn-cine:hover {
            background-color: #b20610;
            color: white;
        }
        .card-pelicula {
            background-color: #1a1a1a;
            border: 1px solid #ffffff;
            transition: transform 0.2s, border-color 0.2s;
        }
        .card-pelicula:hover {
            transform: translateY(-5px);
            border-color: #e50914;
        }
        .badge-genero {
            background-color: #e50914;
            color: white;
        }
        .footer-cine {
            background-color: #1a1a1a;
            border-top: 2px solid #e50914;
            color: #ffffff;
        }
        @yield('estilos')
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-cine px-4">
        <a class="navbar-brand" href="{{ route('cartelera') }}">
            🎬 CINEAPP
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cartelera') }}">
                        <i class="bi bi-film"></i> Cartelera
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('mis.boletos') }}">
                        <i class="bi bi-ticket-perforated"></i> Mis Boletos
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <span class="nav-link text-warning">
                        <i class="bi bi-person-circle"></i>
                        {{ Auth::user()->name }}
                    </span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm ms-2">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    {{-- CONTENIDO --}}
    <main class="container py-4">
        {{-- Mensajes de error o éxito --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('contenido')
    </main>

    {{-- FOOTER --}}
    <footer class="footer-cine text-center py-3 mt-5">
        <p class="mb-0">🎬 CineApp &copy; {{ date('Y') }} - Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>