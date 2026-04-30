@extends('layouts.app')
@section('title', 'Panel Proveedor')
@section('page-title', 'Mi tienda')
@section('page-subtitle', 'Gestiona tus productos y pedidos')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-purple mb-3"><i class="bi bi-box-seam"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $productosCount }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">Mis productos</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-orange mb-3"><i class="bi bi-hourglass-split"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $pedidosPendientes }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">Pedidos pendientes</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-blue mb-3"><i class="bi bi-truck"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $enviosActivos }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">Envíos activos</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-green mb-3"><i class="bi bi-currency-dollar"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">
                <a href="{{ route('proveedor.pedidos.index') }}" style="color:inherit;text-decoration:none;font-size:0.85rem;">Ver pedidos</a>
            </div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">Gestión completa</div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;">Mis productos recientes</div>
            <div style="font-size:0.75rem;color:#94a3b8;">Últimos publicados</div>
        </div>
        <a href="{{ route('proveedor.productos.create') }}" class="btn btn-primary btn-sm" style="border-radius:8px;font-size:0.78rem;">
            <i class="bi bi-plus-lg me-1"></i> Nuevo producto
        </a>
    </div>

    @if($productosRecientes->isEmpty())
    <div class="text-center py-4" style="color:#94a3b8;font-size:0.85rem;">
        <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
        Aún no tienes productos publicados
    </div>
    @else
    <table class="table" style="font-size:0.82rem;">
        <thead>
            <tr style="color:#94a3b8;">
                <th style="font-weight:500;border:none;">Producto</th>
                <th style="font-weight:500;border:none;">Precio</th>
                <th style="font-weight:500;border:none;">Stock</th>
                <th style="font-weight:500;border:none;">Estado</th>
                <th style="font-weight:500;border:none;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productosRecientes as $p)
            <tr style="border-color:#f8fafc;">
                <td style="color:#334155;font-weight:500;">{{ $p->nombre }}</td>
                <td style="font-weight:600;color:#0f172a;">{{ $p->precio_formateado }}</td>
                <td style="color:#64748b;">{{ $p->stock }} unid.</td>
                <td>
                    @if($p->activo && $p->stock > 0)
                        <span style="background:#dcfce7;color:#16a34a;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">Activo</span>
                    @else
                        <span style="background:#fee2e2;color:#dc2626;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">Agotado</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('proveedor.productos.edit', $p) }}" class="btn btn-sm" style="background:#f1f5f9;color:#475569;border-radius:7px;font-size:0.75rem;padding:4px 10px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
