<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Gestión - Telebachillerato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Estilos generales */
        .navbar-brand {
            font-weight: bold;
        }
        
        .nav-tabs .nav-link {
            font-size: 1.1rem;
            padding: 0.8rem 1.5rem;
        }
        
        .nav-tabs .nav-link i {
            margin-right: 8px;
        }
        
        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 3px solid #0d6efd;
        }
        
        .tab-content {
            min-height: 500px;
        }
        
        /* Estilos para la paginación */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .pagination .page-item {
            display: inline-block;
            list-style: none;
        }
        
        .pagination .page-link {
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 4px;
            color: #0d6efd;
            background-color: #fff;
            border: 1px solid #dee2e6;
            text-decoration: none;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        /* Estilos para tarjetas */
        .card-header {
            background-color: #0d6efd;
            color: white;
        }
        
        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }
        
        .footer {
            margin-top: 50px;
            padding: 20px 0;
        }
        
        /* Botones */
        .btn-sm {
            margin: 2px;
        }
        
        /* Tablas */
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
            cursor: pointer;
        }
        
        /* Dropdown menu */
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .dropdown-item i {
            margin-right: 8px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-mortarboard-fill"></i>
                Sistema de Gestión - Telebachillerato
            </a>
            
            @auth
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <span class="dropdown-item-text">
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-danger"><i class="bi bi-shield-lock"></i> Administrador</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-eye"></i> Consultor</span>
                            @endif
                        </span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @if(Auth::user()->isAdmin())
                    <li>
                        <a class="dropdown-item" href="{{ route('usuarios.index') }}">
                            <i class="bi bi-people"></i> Gestionar Usuarios
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left;">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @else
            <div>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn btn-light btn-sm">Registrarse</a>
            </div>
            @endauth
        </div>
    </nav>

    @auth
    <div class="container mt-4">
        <ul class="nav nav-tabs" id="mainTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" 
                   href="{{ route('home') }}">
                    <i class="bi bi-house-door"></i> Inicio
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('centros.*') ? 'active' : '' }}" 
                   href="{{ route('centros.index') }}">
                    <i class="bi bi-building"></i> Centros
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('alumnos.*') ? 'active' : '' }}" 
                   href="{{ route('alumnos.index') }}">
                    <i class="bi bi-people"></i> Alumnos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('calificaciones.*') ? 'active' : '' }}" 
                   href="{{ route('calificaciones.index') }}">
                    <i class="bi bi-star"></i> Calificaciones
                </a>
            </li>
        </ul>

        <div class="mt-4">
            @yield('content')
        </div>
    </div>
    @else
    <div class="container mt-5">
        @yield('content')
    </div>
    @endauth

    <footer class="footer mt-5 py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">
                <i class="bi bi-c-circle"></i> 2024 Sistema de Gestión de Telebachillerato
            </span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>