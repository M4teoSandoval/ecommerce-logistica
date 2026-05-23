@extends('layouts.app')
@section('title', 'Inicio')
@section('page-title', 'Bienvenido, ' . $user->name)
@section('page-subtitle', 'Descubre productos y realiza tus pedidos')
@section('content')

<div class="row g-3 mb-4">
    @php
        $stats = [
            ['Pedidos realizados', $totalPedidos, 'icon-purple', 'bi-bag-check'],
            ['En camino', $enCamino, 'icon-blue', 'bi-truck'],
            ['Entregados', $entregados, 'icon-green', 'bi-check-circle'],
            ['Pendientes', $pendientes, 'icon-orange', 'bi-clock'],
        ];
    @endphp
    @foreach($stats as $s)
    <div class="col-md-3 col-6">
        <div class="stat-card text-center text-md-start">
            <div class="stat-icon {{ $s[2] }} mb-3 mx-auto mx-md-0"><i class="bi {{ $s[3] }}"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $s[1] }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">{{ $s[0] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div style="font-size:0.95rem;font-weight:600;color:#0f172a;">Mis pedidos recientes</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">Historial de compras</div>
                </div>
                @if($totalPedidos > 0)
                    <a href="{{ route('cliente.pedidos.index') }}" style="font-size:0.78rem;color:#6366f1;font-weight:600;text-decoration:none;">
                        Ver todos <i class="bi bi-chevron-right"></i>
                    </a>
                @endif
            </div>
            @forelse($recientes as $p)
            <a href="{{ route('cliente.seguimiento.index', $p) }}" class="d-flex align-items-center gap-3 p-3 mb-2 text-decoration-none" style="background:#f8fafc;border-radius:10px;transition:background 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                <div class="stat-icon icon-purple" style="width:40px;height:40px;font-size:1rem;flex-shrink:0;">
                    <i class="bi {{ $p->transporte_icon }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.82rem;font-weight:600;color:#334155;">Pedido #{{ $p->id }} · {{ $p->items->first()?->producto?->nombre ?? 'Varios productos' }}</div>
                    <div style="font-size:0.72rem;color:#94a3b8;">{{ $p->created_at->format('d/m/Y') }} · {{ ucfirst($p->transporte) }}</div>
                </div>
                <div class="text-end">
                    <div style="font-size:0.82rem;font-weight:700;color:#0f172a;">{{ $p->total_formateado }}</div>
                    <span style="{{ $p->estado_color }};padding:2px 7px;border-radius:5px;font-size:0.68rem;font-weight:600;">
                        {{ ucfirst(str_replace('_', ' ', $p->estado)) }}
                    </span>
                </div>
            </a>
            @empty
            <div class="text-center py-4" style="color:#94a3b8;font-size:0.85rem;">
                Aún no tienes pedidos. <a href="{{ route('cliente.productos.index') }}" style="color:#6366f1;">Compra ahora</a>
            </div>
            @endforelse
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:4px;">Accesos rápidos</div>
            <div style="font-size:0.75rem;color:#94a3b8;margin-bottom:16px;">¿Qué quieres hacer?</div>
            @php
                $accesos = [
                    ['Buscar productos', 'Explora el catálogo', 'bi-search', 'icon-purple', route('cliente.productos.index')],
                    ['Ver mi carrito', $pedidos->isNotEmpty() ? 'Tienes pedidos activos' : 'Realiza tu primera compra', 'bi-cart3', 'icon-blue', route('cliente.carrito.index')],
                    ['Mis pedidos', 'Seguimiento y facturación', 'bi-bag-check', 'icon-green', route('cliente.pedidos.index')],
                    ['Mis facturas', 'Descarga tu factura electrónica', 'bi-receipt', 'icon-teal', route('cliente.facturas.index')],
                ];
            @endphp
            @foreach($accesos as $a)
            <a href="{{ $a[4] }}" class="d-flex align-items-center gap-3 p-3 mb-2 text-decoration-none" style="background:#f8fafc;border-radius:10px;transition:background 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
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
