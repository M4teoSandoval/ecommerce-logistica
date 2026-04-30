<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SwiftDrop')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f2f5; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: all 0.3s;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
            letter-spacing: -0.3px;
        }
        .sidebar-brand span {
            color: #6366f1;
        }
        .sidebar-brand small {
            color: #64748b;
            font-size: 0.7rem;
            display: block;
            margin-top: 2px;
        }
        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }
        .nav-section-label {
            color: #475569;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 8px 8px 4px;
            margin-top: 8px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 450;
            transition: all 0.2s;
            margin-bottom: 2px;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.06);
            color: #e2e8f0;
        }
        .sidebar-link.active {
            background: rgba(99,102,241,0.15);
            color: #818cf8;
        }
        .sidebar-link i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
        }
        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            flex-shrink: 0;
        }
        .user-info small {
            color: #64748b;
            font-size: 0.68rem;
            display: block;
        }
        .user-info span {
            color: #e2e8f0;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Topbar */
        .topbar {
            margin-left: 260px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
        }
        .topbar-subtitle {
            font-size: 0.75rem;
            color: #94a3b8;
        }
        .role-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 99px;
            text-transform: capitalize;
        }
        .role-badge.administrador { background: #ede9fe; color: #7c3aed; }
        .role-badge.proveedor     { background: #dcfce7; color: #16a34a; }
        .role-badge.cliente       { background: #dbeafe; color: #1d4ed8; }
        .role-badge.repartidor    { background: #ffedd5; color: #ea580c; }

        /* Main content */
        .main-content {
            margin-left: 260px;
            padding: 28px;
            min-height: calc(100vh - 64px);
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            border: 1px solid #f1f5f9;
            transition: box-shadow 0.2s;
        }
        .stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .icon-purple { background: #ede9fe; color: #7c3aed; }
        .icon-blue   { background: #dbeafe; color: #1d4ed8; }
        .icon-green  { background: #dcfce7; color: #16a34a; }
        .icon-orange { background: #ffedd5; color: #ea580c; }
        .icon-red    { background: #fee2e2; color: #dc2626; }
        .icon-teal   { background: #ccfbf1; color: #0d9488; }

        .content-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            padding: 24px;
        }

        /* Buttons */
        .btn-primary {
            background: #6366f1;
            border-color: #6366f1;
        }
        .btn-primary:hover {
            background: #4f46e5;
            border-color: #4f46e5;
        }

        /* Alerts */
        .alert { border-radius: 10px; border: none; }
        .alert-danger { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #dcfce7; color: #166534; }

        /* Auth pages */
        .auth-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #312e81 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        }
        .auth-logo {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            margin: 0 auto 20px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }
        .btn-auth {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 10px;
            padding: 11px;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            width: 100%;
            transition: opacity 0.2s, transform 0.1s;
        }
        .btn-auth:hover { opacity: 0.92; transform: translateY(-1px); color: white; }
        .btn-logout {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: #94a3b8;
            font-size: 0.78rem;
            border-radius: 7px;
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.15); color: #f87171; border-color: rgba(239,68,68,0.3); }
    </style>
    @stack('styles')
</head>
<body>

@auth
<div class="sidebar">
    <div class="sidebar-brand">
        <h5><span>Swift</span>Drop</h5>
        <small>Sistema de comercio y logística</small>
    </div>
    <nav class="sidebar-nav">
        @if(Auth::user()->isAdmin())
            <div class="nav-section-label">General</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <div class="nav-section-label">Logística</div>
            <a href="{{ route('admin.drones.index') }}" class="sidebar-link">
                <i class="bi bi-send"></i> Drones
            </a>
            <a href="{{ route('admin.mantenimientos.index') }}" class="sidebar-link">
                <i class="bi bi-truck"></i> Mantenimientos
            </a>
            <a href="{{ route('admin.simulacion.index') }}" class="sidebar-link">
                <i class="bi bi-map"></i> Simular ruta
            </a>
            <a href="{{ route('admin.seguimiento.index') }}" class="sidebar-link">
                <i class="bi bi-geo-alt"></i> Seguimiento
            </a>
            <div class="nav-section-label">Administración</div>
            <a href="#" class="sidebar-link">
                <i class="bi bi-people"></i> Usuarios
            </a>
            <a href="#" class="sidebar-link">
                <i class="bi bi-shop"></i> Proveedores
            </a>
            <a href="#" class="sidebar-link">
                <i class="bi bi-graph-up"></i> Reportes
            </a>
        @elseif(Auth::user()->isProveedor())
            <div class="nav-section-label">Mi Tienda</div>
            <a href="{{ route('proveedor.dashboard') }}" class="sidebar-link {{ request()->routeIs('proveedor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <a href="{{ route('proveedor.productos.index') }}" class="sidebar-link">
                <i class="bi bi-box-seam"></i> Mis Productos
            </a>
            <div class="nav-section-label">Gestión</div>
            <a href="{{ route('proveedor.pedidos.index') }}" class="sidebar-link {{ request()->routeIs('proveedor.pedidos.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Pedidos
            </a>
            <a href="{{ route('proveedor.envios.index') }}" class="sidebar-link {{ request()->routeIs('proveedor.envios.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> Envíos
            </a>
        @elseif(Auth::user()->isRepartidor())
            <div class="nav-section-label">Mi Panel</div>
            <a href="{{ route('repartidor.dashboard') }}" class="sidebar-link {{ request()->routeIs('repartidor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <div class="nav-section-label">Entregas</div>
            <a href="{{ route('repartidor.entregas.index') }}" class="sidebar-link {{ request()->routeIs('repartidor.entregas.*') ? 'active' : '' }}">
                <i class="bi bi-bicycle"></i> Mis Entregas
            </a>
        @else
            <div class="nav-section-label">Tienda</div>
            <a href="{{ route('cliente.dashboard') }}" class="sidebar-link {{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Inicio
            </a>
            <a href="{{ route('cliente.productos.index') }}" class="sidebar-link">
                <i class="bi bi-search"></i> Productos
            </a>
            <a href="{{ route('cliente.carrito.index') }}" class="sidebar-link">
                <i class="bi bi-cart3"></i> Mi Carrito
            </a>
            <div class="nav-section-label">Mis Compras</div>
            <a href="{{ route('cliente.pedidos.index') }}" class="sidebar-link">
                <i class="bi bi-bag-check"></i> Mis Pedidos
            </a>
        @endif
    </nav>
    <div class="sidebar-footer">
        <div class="user-pill">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info" style="flex:1; overflow:hidden;">
                <span>{{ Auth::user()->name }}</span>
                <small>{{ Auth::user()->email }}</small>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn-logout" title="Cerrar sesión"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </div>
</div>

<div class="topbar">
    <div>
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-subtitle">@yield('page-subtitle', '')</div>
    </div>
    <span class="role-badge {{ Auth::user()->role }}">{{ Auth::user()->role }}</span>
</div>

<main class="main-content">
    @yield('content')
</main>

@else
    @yield('content')
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>