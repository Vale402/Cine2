<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CineApp — Panel de Administración">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CineApp Admin — @yield('titulo', 'Panel')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CineApp Design System --}}
    <link href="{{ asset('css/cine.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cine-admin.css') }}" rel="stylesheet">
    @yield('estilos')
</head>
<body>

    {{-- Mobile Toggle --}}
    <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Menú">
        <i class="bi bi-list"></i>
    </button>

    {{-- Sidebar Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="admin-wrapper">

        {{-- SIDEBAR --}}
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <h2>🎬 CINEAPP</h2>
                <small>Panel Admin</small>
            </div>

            <nav class="sidebar-nav">
                {{-- Principal --}}
                <div class="sidebar-section">
                    <div class="sidebar-section-label">Principal</div>
                    <a href="{{ route('admin.dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </div>

                {{-- Gestión --}}
                <div class="sidebar-section">
                    <div class="sidebar-section-label">Gestión</div>
                    <a href="{{ route('admin.peliculas') }}"
                       class="sidebar-link {{ request()->routeIs('admin.peliculas*') ? 'active' : '' }}">
                        <i class="bi bi-film"></i> Películas
                    </a>
                    <a href="{{ route('admin.salas') }}"
                       class="sidebar-link {{ request()->routeIs('admin.salas*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> Salas
                    </a>
                    <a href="{{ route('admin.funciones') }}"
                       class="sidebar-link {{ request()->routeIs('admin.funciones*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event"></i> Funciones
                    </a>
                </div>

                {{-- Ventas --}}
                <div class="sidebar-section">
                    <div class="sidebar-section-label">Ventas</div>
                    <a href="{{ route('admin.boletos') }}"
                       class="sidebar-link {{ request()->routeIs('admin.boletos') ? 'active' : '' }}">
                        <i class="bi bi-ticket-perforated"></i> Boletos
                    </a>
                </div>

                {{-- Sistema --}}
                <div class="sidebar-section">
                    <div class="sidebar-section-label">Sistema</div>
                    <a href="{{ route('admin.usuarios') }}"
                       class="sidebar-link {{ request()->routeIs('admin.usuarios') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                    <a href="{{ route('cartelera') }}" class="sidebar-link">
                        <i class="bi bi-box-arrow-up-right"></i> Ver Sitio
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                        <div class="sidebar-user-role">Administrador</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                        @csrf
                        <button type="submit" class="action-btn action-btn-delete" title="Cerrar sesión"
                                style="width:28px;height:28px;font-size:0.8rem;">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="admin-main">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>
    @yield('scripts')
</body>
</html>
