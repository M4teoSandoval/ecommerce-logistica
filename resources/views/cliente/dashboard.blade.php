@extends('layouts.app')
@section('title', 'Inicio')
@section('page-title', 'Bienvenido')
@section('page-subtitle', 'Descubre productos y realiza tus pedidos')
@section('content')

<div class="row g-3 mb-4">
    @foreach([
        ['Pedidos realizados','8','icon-purple','bi-bag-check'],
        ['En camino','2','icon-blue','bi-truck'],
        ['Entregados','6','icon-green','bi-check-circle'],
        ['Puntos acumulados','320','icon-orange','bi-star'],
    ] as $s)
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon {{ $s[2] }} mb-3"><i class="bi {{ $s[3] }}"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $s[1] }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">{{ $s[0] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:4px;">Mis pedidos recientes</div>
            <div style="font-size:0.75rem;color:#94a3b8;margin-bottom:20px;">Historial de compras</div>
            @foreach([
                ['#001','Audífonos Bluetooth','Dron','Entregado','$89.000'],
                ['#002','Mouse Inalámbrico','Moto','En camino','$45.000'],
                ['#003','Teclado Mecánico','Furgoneta','Pendiente','$230.000'],
            ] as $p)
            <div class="d-flex align-items-center gap-3 p-3 mb-2" style="background:#f8fafc;border-radius:10px;">
                <div class="stat-icon icon-purple" style="width:40px;height:40px;font-size:1rem;flex-shrink:0;">
                    <i class="bi bi-bag"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.82rem;font-weight:600;color:#334155;">{{ $p[1] }}</div>
                    <div style="font-size:0.72rem;color:#94a3b8;">Pedido {{ $p[0] }} · {{ $p[2] }}</div>
                </div>
                <div class="text-end">
                    <div style="font-size:0.82rem;font-weight:700;color:#0f172a;">{{ $p[4] }}</div>
                    @if($p[3]=='Entregado')
                        <span style="background:#dcfce7;color:#16a34a;padding:2px 7px;border-radius:5px;font-size:0.68rem;font-weight:600;">Entregado</span>
                    @elseif($p[3]=='En camino')
                        <span style="background:#fef9c3;color:#854d0e;padding:2px 7px;border-radius:5px;font-size:0.68rem;font-weight:600;">En camino</span>
                    @else
                        <span style="background:#f1f5f9;color:#64748b;padding:2px 7px;border-radius:5px;font-size:0.68rem;font-weight:600;">Pendiente</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:4px;">Accesos rápidos</div>
            <div style="font-size:0.75rem;color:#94a3b8;margin-bottom:16px;">¿Qué quieres hacer?</div>
            @foreach([
                ['Buscar productos','Explora el catálogo','bi-search','icon-purple'],
                ['Ver mi carrito','2 productos','bi-cart3','icon-blue'],
                ['Seguir mi pedido','Pedido #002 en camino','bi-geo-alt','icon-green'],
            ] as $a)
            <a href="#" class="d-flex align-items-center gap-3 p-3 mb-2 text-decoration-none" style="background:#f8fafc;border-radius:10px;transition:background 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                <div class="stat-icon {{ $a[3] }}" style="width:38px;height:38px;font-size:0.95rem;flex-shrink:0;">
                    <i class="bi {{ $a[2] }}"></i>
                </div>
                <div>
                    <div style="font-size:0.82rem;font-weight:600;color:#334155;">{{ $a[0] }}</div>
                    <div style="font-size:0.72rem;color:#94a3b8;">{{ $a[1] }}</div>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color:#cbd5e1;font-size:0.8rem;"></i>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection