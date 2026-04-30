<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>SwiftDrop — Comercio inteligente con logística avanzada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #0f172a;
            overflow-x: hidden;
        }

        /* Glassmorphism Nav */
        .navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            transition: all 0.25s ease;
        }

        .nav-logo {
            font-size: 1.35rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b, #4f46e5);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.02em;
        }
        .nav-logo span { color: #4f46e5; background: none; -webkit-background-clip: unset; background-clip: unset; }

        .nav-link-custom {
            font-weight: 500;
            font-size: 0.9rem;
            color: #334155;
            text-decoration: none;
            transition: 0.2s;
        }
        .nav-link-custom:hover { color: #4f46e5; }
        .btn-nav {
            background: #4f46e5;
            color: white;
            border-radius: 40px;
            padding: 0.5rem 1.4rem;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
        }
        .btn-nav:hover { background: #6366f1; transform: translateY(-1px); color: white; }

        /* Hero con gradiente moderno */
        .hero {
            min-height: 100vh;
            background: radial-gradient(circle at 10% 20%, rgba(15,23,42,1) 0%, rgba(30,41,59,1) 70%, #0f172a 100%);
            position: relative;
            display: flex;
            align-items: center;
            padding: 120px 0 80px;
            overflow: hidden;
        }
        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MCIgaGVpZ2h0PSI4MCIgdmlld0JveD0iMCAwIDQwIDQwIj48cGF0aCBmaWxsPSIjM2IzYjgyIiBmaWxsLW9wYWNpdHk9IjAuMDYiIGQ9Ik0wIDBoNDB2NDBIMHoiLz48L3N2Zz4=');
            opacity: 0.3;
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(99,102,241,0.2);
            backdrop-filter: blur(2px);
            border: 1px solid rgba(99,102,241,0.4);
            border-radius: 60px;
            padding: 6px 18px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #c7d2fe;
            margin-bottom: 24px;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.15;
            background: linear-gradient(to right, #ffffff, #c7d2fe, #a5b4fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .hero p {
            font-size: 1.05rem;
            color: #94a3b8;
            line-height: 1.6;
            max-width: 520px;
        }
        .btn-hero-primary {
            background: #4f46e5;
            border-radius: 40px;
            padding: 12px 28px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            box-shadow: 0 8px 18px rgba(79, 70, 229, 0.25);
        }
        .btn-hero-primary:hover { background: #6366f1; transform: translateY(-2px); color: white; }
        .btn-hero-secondary {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 40px;
            padding: 12px 28px;
            font-weight: 500;
            color: #e2e8f0;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-hero-secondary:hover { background: rgba(255,255,255,0.12); color: white; }

        /* Login card elegant */
        .login-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 2rem;
            transition: all 0.2s;
        }
        .login-card .form-control {
            background: rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1rem;
            padding: 0.7rem 1rem;
            color: #f1f5f9;
            font-size: 0.9rem;
        }
        .login-card .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.3);
            background: #0f172a;
        }
        .btn-login {
            background: linear-gradient(105deg, #4f46e5, #8b5cf6);
            border: none;
            border-radius: 2rem;
            padding: 12px;
            font-weight: 700;
            width: 100%;
            transition: 0.2s;
        }
        .btn-login:hover { opacity: 0.9; transform: scale(0.98); }

        /* Stats */
        .stats-section {
            background: #f8fafc;
            padding: 4rem 0;
            border-bottom: 1px solid #eef2ff;
        }
        .stat-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: 0.2s;
        }
        .stat-num {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b, #4f46e5);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* Feature cards mejoradas */
        .feature-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.8rem;
            transition: all 0.3s;
            border: 1px solid #f1f5f9;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: #cbd5e1;
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.08);
        }

        /* TRANSPORTE con imágenes reales SVG / ilustraciones */
        .transport-img-card {
            background: #0f172a;
            border-radius: 1.8rem;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .transport-img-card:hover { transform: translateY(-6px); border-color: #4f46e5; }
        .transport-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(145deg, #1e293b, #0f172a);
            transition: transform 0.5s;
        }
        .transport-img-card:hover .transport-img { transform: scale(1.02); }
        .transport-badge {
            background: #4f46e5;
            border-radius: 40px;
            padding: 0.2rem 0.8rem;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-block;
        }

        /* Step cards */
        .step-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid #eef2ff;
            height: 100%;
            transition: all 0.2s;
        }
        .step-number {
            width: 44px;
            height: 44px;
            background: linear-gradient(145deg, #4f46e5, #8b5cf6);
            border-radius: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            margin: 0 auto 1rem;
            color: white;
        }

        /* CTA */
        .cta-section {
            background: linear-gradient(115deg, #0f172a, #1e1b4b);
            border-radius: 0;
        }
        .btn-cta-white {
            background: white;
            color: #4f46e5;
            border-radius: 40px;
            padding: 0.8rem 2rem;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-cta-white:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); color: #4338ca; }

        footer {
            background: #020617;
        }
        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.8rem;
            transition: 0.2s;
        }
        .footer-link:hover { color: #a5b4fc; }
        @media (max-width: 768px) {
            .hero { padding: 100px 0 60px; }
            .login-card { margin-top: 2rem; }
        }
        .demo-btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 2rem;
            padding: 0.4rem 0.8rem;
            font-size: 0.7rem;
            transition: 0.2s;
        }
        .demo-btn:hover { background: rgba(99,102,241,0.3); border-color: #6366f1; }

        /* QUIENES SOMOS */
        .about-section {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }
        .about-text {
            font-size: 1.05rem;
            line-height: 1.7;
            color: #475569;
        }
        .about-icon-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(145deg, #ede9fe, #dbeafe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #4f46e5;
            flex-shrink: 0;
        }
        .about-value-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.8rem;
            border: 1px solid #eef2ff;
            height: 100%;
            transition: all 0.3s;
        }
        .about-value-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.08);
            border-color: #c7d2fe;
        }
        .about-value-card h6 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
        }
        .about-value-card p {
            font-size: 0.82rem;
            color: #64748b;
            margin: 0;
            line-height: 1.5;
        }
        .team-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem 1.5rem;
            text-align: center;
            border: 1px solid #eef2ff;
            height: 100%;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .team-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(135deg, #4f46e5, #8b5cf6);
            opacity: 0.06;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }
        .team-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            position: relative;
            z-index: 1;
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.25);
        }
        .team-card h6 {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.3rem;
        }
        .team-role {
            font-size: 0.78rem;
            font-weight: 600;
            color: #6366f1;
            background: #ede9fe;
            padding: 0.2rem 0.7rem;
            border-radius: 99px;
            display: inline-block;
            margin-bottom: 0.8rem;
        }
        .team-card p {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar-custom">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="/" class="nav-logo fs-3 fw-bold text-decoration-none"><span>Swift</span>Drop</a>
        <div class="d-none d-md-flex gap-4">
            <a href="#features" class="nav-link-custom">Funcionalidades</a>
            <a href="#transporte" class="nav-link-custom">Transporte</a>
            <a href="#como-funciona" class="nav-link-custom">Cómo funciona</a>
            <a href="#quienes-somos" class="nav-link-custom">Quiénes somos</a>
            <a href="#demo" class="nav-link-custom">Demo</a>
        </div>
        <div class="d-flex gap-2">
            <a href="/register" class="nav-link-custom d-none d-sm-inline-block align-self-center me-2">Registro</a>
            <a href="#login" class="btn-nav">Iniciar sesión</a>
        </div>
    </div>
</nav>

<!-- HERO con login -->
<section class="hero" id="hero">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-send-fill"></i> Logística aumentada con IA
                </div>
                <h1>Comercio electrónico <br> con <span style="background: linear-gradient(145deg,#a5b4fc,#c7d2fe);-webkit-background-clip:text;background-clip:text;color:transparent;">entrega inteligente</span></h1>
                <p class="mt-3">Compra, vende y recibe tus productos con la red logística más avanzada: drones autónomos, motos eco y furgonetas conectadas en tiempo real.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="/register" class="btn-hero-primary"><i class="bi bi-rocket-takeoff"></i> Comenzar ahora</a>
                    <a href="#como-funciona" class="btn-hero-secondary"><i class="bi bi-play-circle"></i> Ver demo</a>
                </div>
                <div class="d-flex gap-4 mt-5">
                    <div><div class="fw-bold text-white fs-3">3+</div><div class="text-secondary-emphasis small">Medios de transporte</div></div>
                    <div><div class="fw-bold text-white fs-3">99.5%</div><div class="text-secondary-emphasis small">Disponibilidad</div></div>
                    <div><div class="fw-bold text-white fs-3">10s</div><div class="text-secondary-emphasis small">Actualización GPS</div></div>
                </div>
            </div>

            <div class="col-lg-5 offset-lg-1" id="login">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <div class="mx-auto mb-2 bg-gradient rounded-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; background: linear-gradient(145deg,#4f46e5,#7c3aed); border-radius: 1.2rem;">
                            <i class="bi bi-box-seam fs-3 text-white"></i>
                        </div>
                        <h5 class="text-white fw-bold">Acceso seguro</h5>
                        <p class="small text-secondary">Ingresa a tu cuenta de SwiftDrop</p>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger small py-2 rounded-4">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger small py-2 rounded-4">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="/login">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-light small fw-semibold">Correo electrónico</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="usuario@ecommerce.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-light small fw-semibold">Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn-login text-white"><i class="bi bi-arrow-right-circle me-2"></i> Iniciar sesión</button>
                    </form>
                    <div class="mt-4 pt-2 text-center border-top border-light-subtle">
                        <span class="small text-secondary">¿Sin cuenta? </span>
                        <a href="/register" class="text-primary fw-semibold text-decoration-none">Regístrate gratis</a>
                    </div>
                    <div class="mt-3">
                        <div class="text-center small text-secondary mb-2">🔐 Acceso rápido demostración</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button onclick="fillLogin('admin@ecommerce.com','admin123')" class="demo-btn w-100 text-white">
                                    <i class="bi bi-person-badge"></i> Administrador
                                </button>
                            </div>
                            <div class="col-6">
                                <button onclick="fillLogin('cliente@ecommerce.com','cliente123')" class="demo-btn w-100 text-white">
                                    <i class="bi bi-person"></i> Cliente
                                </button>
                            </div>
                            <div class="col-6">
                                <button onclick="fillLogin('proveedor@ecommerce.com','proveedor123')" class="demo-btn w-100 text-white">
                                    <i class="bi bi-shop"></i> Proveedor
                                </button>
                            </div>
                            <div class="col-6">
                                <button onclick="fillLogin('repartidor@test.com','12345678')" class="demo-btn w-100 text-white">
                                    <i class="bi bi-bicycle"></i> Repartidor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS mejorados -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4 text-center justify-content-center">
            <div class="col-md-3 col-6">
                <div class="stat-card"><i class="bi bi-truck fs-1 text-primary"></i><div class="stat-num mt-2">3+</div><div class="text-secondary">Medios de transporte</div></div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card"><i class="bi bi-shield-check fs-1 text-primary"></i><div class="stat-num mt-2">99.5%</div><div class="text-secondary">Entregas a tiempo</div></div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card"><i class="bi bi-satellite fs-1 text-primary"></i><div class="stat-num mt-2">10s</div><div class="text-secondary">Actualización en vivo</div></div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card"><i class="bi bi-receipt fs-1 text-primary"></i><div class="stat-num mt-2">100%</div><div class="text-secondary">Facturación DIAN</div></div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features-section py-5" id="features">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">PLATAFORMA INTELIGENTE</span>
            <h2 class="display-6 fw-bold mt-3">Todo lo que necesitas en un solo lugar</h2>
            <p class="text-secondary w-75 mx-auto">Gestión completa de usuarios, productos, carrito y seguimiento logístico con tecnología de punta.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><div class="feature-card"><i class="bi bi-person-badge fs-1 text-primary"></i><h5 class="fw-bold mt-3">Gestión de usuarios</h5><p class="text-secondary small">Roles: clientes, proveedores y administradores con paneles personalizados y seguridad JWT.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><i class="bi bi-grid-3x3-gap-fill fs-1 text-primary"></i><h5 class="fw-bold mt-3">Catálogo dinámico</h5><p class="text-secondary small">Filtros por categoría, precio y proveedor. Carga masiva de productos para vendedores.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><i class="bi bi-cart-check fs-1 text-primary"></i><h5 class="fw-bold mt-3">Carrito + checkout</h5><p class="text-secondary small">Selecciona transporte, calcula costo en tiempo real y confirma en pasos simples.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><i class="bi bi-drone fs-1 text-primary"></i><h5 class="fw-bold mt-3">Flota de drones</h5><p class="text-secondary small">Gestión de batería, rutas simuladas y mantenimiento predictivo de drones.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><i class="bi bi-map fs-1 text-primary"></i><h5 class="fw-bold mt-3">Seguimiento en tiempo real</h5><p class="text-secondary small">Actualización GPS cada 10 segundos + timeline completa del pedido.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><i class="bi bi-file-text-fill fs-1 text-primary"></i><h5 class="fw-bold mt-3">Facturación electrónica</h5><p class="text-secondary small">Cumple requisitos DIAN Colombia, genera XML y PDF automáticos.</p></div></div>
        </div>
    </div>
</section>

<!-- TRANSPORTE con IMAGENES reales (ilustraciones vectoriales / SVG modernos) -->
<section class="transport-section py-5" id="transporte" style="background: #0b1120;">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-light text-dark bg-opacity-25 rounded-pill px-3 py-2">MOVILIDAD INTELIGENTE</span>
            <h2 class="display-6 fw-bold text-white mt-2">Elige tu medio de transporte</h2>
            <p class="text-secondary-emphasis w-75 mx-auto">Optimización automática según peso, distancia y urgencia.</p>
        </div>
        <div class="row g-4">
            <!-- Drone card con imagen realista (SVG data) -->
            <div class="col-md-4">
                <div class="transport-img-card">
                    <img class="transport-img" src="https://easy-trans.es/wp-content/uploads/2025/08/robot-de-entrega-en-un-entorno-futurista.jpg" alt="Drone ecológico">
                    <div class="p-3">
                        <div class="d-flex justify-content-between"><h5 class="text-white fw-bold">🚁 Dron X1</h5><span class="transport-badge text-white">+ eficiente</span></div>
                        <p class="text-secondary small mt-2">Entregas ultrarrápidas para paquetes ≤2kg, cobertura 15km, velocidad 60km/h.</p>
                        <div class="d-flex gap-2 mt-2"><i class="bi bi-battery-full text-success"></i><span class="text-white-50 small">Autonomía 35min</span><i class="bi bi-geo-alt ms-2 text-info"></i><span class="text-white-50 small">Precisión 1m</span></div>
                    </div>
                </div>
            </div>
            <!-- Moto con imagen -->
            <div class="col-md-4">
                <div class="transport-img-card">
                    <img class="transport-img" src="https://thumbs.dreamstime.com/b/mercanc%C3%ADas-del-transporte-de-los-trabajadores-en-moto-y-carro-63406499.jpg" alt="Moto">
                    <div class="p-3">
                        <h5 class="text-white fw-bold">🏍️ Moto </h5>
                        <p class="text-secondary small">Entregas locales en 30min, capacidad hasta 10kg. La opción más ágil para la ciudad.</p>
                        <div class="mt-2"><span class="badge bg-info">Cero emisiones</span> <span class="badge bg-secondary">Seguro incluido</span></div>
                    </div>
                </div>
            </div>
            <!-- Furgoneta -->
            <div class="col-md-4">
                <div class="transport-img-card">
                    <img class="transport-img" src="https://certifix.es/blog/wp-content/uploads/2024/02/furgoneta-de-carga-certifix-1024x683.jpg" alt="Furgoneta">
                    <div class="p-3">
                        <h5 class="text-white fw-bold">🚚 Furgoneta Pro</h5>
                        <p class="text-secondary small">Paquetes pesados o voluminosos, carga ilimitada nacional, rastreo satelital.</p>
                        <div class="mt-2"><i class="bi bi-box-seam text-warning"></i> <span class="text-white-50 small">Hasta 200kg</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CÓMO FUNCIONA -->
<section class="how-section py-5 bg-white" id="como-funciona">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3 py-2">SIMPLE Y RÁPIDO</span>
            <h2 class="display-6 fw-bold mt-2">Cómo funciona SwiftDrop</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-6"><div class="step-card"><div class="step-number">1</div><i class="bi bi-person-plus fs-1 text-primary"></i><h6 class="fw-bold mt-2">Regístrate</h6><p class="small text-secondary">Cuenta en 1 minuto como cliente o proveedor.</p></div></div>
            <div class="col-md-3 col-6"><div class="step-card"><div class="step-number">2</div><i class="bi bi-search-heart fs-1 text-primary"></i><h6 class="fw-bold mt-2">Explora productos</h6><p class="small text-secondary">Busca, filtra y compara ofertas.</p></div></div>
            <div class="col-md-3 col-6"><div class="step-card"><div class="step-number">3</div><i class="bi bi-truck fs-1 text-primary"></i><h6 class="fw-bold mt-2">Transporte inteligente</h6><p class="small text-secondary">El sistema asigna el mejor medio según tu pedido.</p></div></div>
            <div class="col-md-3 col-6"><div class="step-card"><div class="step-number">4</div><i class="bi bi-geo-alt-fill fs-1 text-primary"></i><h6 class="fw-bold mt-2">Seguimiento real</h6><p class="small text-secondary">Rastrea tu envío en vivo hasta tu puerta.</p></div></div>
        </div>
    </div>
</section>

<!-- QUIÉNES SOMOS -->
<section class="about-section py-5" id="quienes-somos">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">NUESTRO EQUIPO</span>
            <h2 class="display-6 fw-bold mt-3">Quiénes Somos</h2>
            <p class="text-secondary w-75 mx-auto">Somos un equipo de estudiantes apasionados por la tecnología y la innovación, comprometidos con transformar la logística del comercio electrónico.</p>
        </div>

        <div class="row g-5 align-items-center mb-5">
            <div class="col-lg-6">
                <div class="d-flex gap-3 mb-4">
                    <div class="about-icon-circle"><i class="bi bi-lightbulb"></i></div>
                    <div>
                        <h5 class="fw-bold">Nuestra Misión</h5>
                        <p class="about-text mb-0">Democratizar el acceso a logística inteligente para e-commerce, integrando drones y transporte automatizado con seguimiento en tiempo real.</p>
                    </div>
                </div>
                <div class="d-flex gap-3 mb-4">
                    <div class="about-icon-circle"><i class="bi bi-eye"></i></div>
                    <div>
                        <h5 class="fw-bold">Nuestra Visión</h5>
                        <p class="about-text mb-0">Ser la plataforma líder en entregas inteligentes, combinando tecnología de punta con sostenibilidad ambiental.</p>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="about-icon-circle"><i class="bi bi-heart"></i></div>
                    <div>
                        <h5 class="fw-bold">Nuestro Compromiso</h5>
                        <p class="about-text mb-0">Innovar constantemente para ofrecer soluciones logísticas eficientes, accesibles y amigables con el medio ambiente.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="about-value-card text-center">
                            <i class="bi bi-rocket-takeoff fs-2 text-primary mb-2"></i>
                            <h6>Innovación</h6>
                            <p>Integramos tecnología emergente en cada proceso logístico.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-value-card text-center">
                            <i class="bi bi-leaf fs-2 text-success mb-2"></i>
                            <h6>Sostenibilidad</h6>
                            <p>Minimizamos el impacto ambiental con entregas ecoeficientes.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-value-card text-center">
                            <i class="bi bi-shield-check fs-2 text-info mb-2"></i>
                            <h6>Confianza</h6>
                            <p>Transparencia total en cada paso de la entrega.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-value-card text-center">
                            <i class="bi bi-people fs-2 text-warning mb-2"></i>
                            <h6>Colaboración</h6>
                            <p>Trabajamos juntos con clientes y proveedores.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <h4 class="fw-bold">Conoce al equipo</h4>
            <p class="text-secondary">Las personas detrás de SwiftDrop</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="team-card">
                    <div class="team-avatar">MS</div>
                    <h6>Mateo Sandoval</h6>
                    <span class="team-role">Desarrollo Backend</span>
                    <p>Arquitectura y lógica del sistema de logística y seguimiento.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="team-card">
                    <div class="team-avatar">CC</div>
                    <h6>Cristian Cala</h6>
                    <span class="team-role">Product Owner</span>
                    <p>Diseño de interfaces y experiencia de usuario.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="team-card">
                    <div class="team-avatar">WS</div>
                    <h6>Wilson Suarez</h6>
                    <span class="team-role">Desarrollador Frontend</span>
                    <p>Modelado de datos y optimización del sistema.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="team-card">
                    <div class="team-avatar">RC</div>
                    <h6>Ralph Castellanos</h6>
                    <span class="team-role">Scrum Master        </span>
                    <p>Validación funcional y aseguramiento de calidad.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA final -->
<section class="cta-section py-5 text-center" id="demo">
    <div class="container py-5">
        <h2 class="text-white fw-bold display-6">Logística del futuro, hoy</h2>
        <p class="text-white-50 mb-4">Únete a la revolución del comercio con entregas inteligentes y tecnología ecoamigable.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="/register" class="btn-cta-white"><i class="bi bi-rocket-takeoff me-2"></i>Crear cuenta gratis</a>
            <a href="#login" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">Iniciar sesión →</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="pt-5 pb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3"><div class="fw-bold text-white fs-5"><span class="text-primary">Eco</span>Logística</div><p class="text-secondary-emphasis small mt-2">Plataforma integral de e-commerce + logística inteligente. Proyecto de innovación tecnológica.</p></div>
            <div class="col-md-2"><h6 class="text-white-50">Plataforma</h6><a href="#features" class="footer-link d-block">Funcionalidades</a><a href="#transporte" class="footer-link d-block">Transporte</a><a href="#como-funciona" class="footer-link d-block">Guía</a></div>
            <div class="col-md-2"><h6 class="text-white-50">Equipo</h6><a href="#quienes-somos" class="footer-link d-block">Quiénes somos</a><a href="#demo" class="footer-link d-block">Demo</a></div>
            <div class="col-md-2"><h6 class="text-white-50">Soporte</h6><a href="#" class="footer-link d-block">Centro ayuda</a><a href="#" class="footer-link d-block">Términos</a></div>
            <div class="col-md-4"><h6 class="text-white-50">Tecnologías</h6><div class="d-flex flex-wrap gap-2"><span class="badge bg-dark bg-opacity-50">Laravel 12</span><span class="badge bg-dark bg-opacity-50">PHP 8.2</span><span class="badge bg-dark bg-opacity-50">MySQL</span><span class="badge bg-dark bg-opacity-50">Bootstrap 5</span></div></div>
        </div>
        <hr class="bg-secondary mt-4">
        <div class="d-flex justify-content-between flex-wrap small text-secondary">© 2026 SwiftDrop — Proyecto académico <span>Mateo Sandoval · Cristian Cala · Wilson Suarez · Ralph Castellanos</span></div>
    </div>
</footer>

<script>
function fillLogin(email, password) {
    let emailInput = document.querySelector('input[name="email"]');
    let passInput = document.querySelector('input[name="password"]');
    if(emailInput && passInput) {
        emailInput.value = email;
        passInput.value = password;
        emailInput.dispatchEvent(new Event('input', { bubbles: true }));
        passInput.dispatchEvent(new Event('input', { bubbles: true }));
    }
}
window.addEventListener('scroll', () => {
    const nav = document.querySelector('.navbar-custom');
    if(window.scrollY > 20) nav.style.boxShadow = '0 8px 20px rgba(0,0,0,0.05)';
    else nav.style.boxShadow = 'none';
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>