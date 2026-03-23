@extends('layouts.app')
@section('title', 'Panel Proveedor')
@section('page-title', 'Mi tienda')
@section('page-subtitle', 'Gestiona tus productos y pedidos')
@section('content')

<div class="row g-3 mb-4">
    @foreach([
        ['Mis productos','24','icon-purple','bi-box-seam'],
        ['Pedidos pendientes','7','icon-orange','bi-hourglass-split'],
        ['Enviados hoy','12','icon-blue','bi-truck'],
        ['Ingresos del mes','$1.8M','icon-green','bi-currency-dollar'],
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

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;">Mis productos recientes</div>
            <div style="font-size:0.75rem;color:#94a3b8;">Últimos publicados</div>
        </div>
        <a href="#" class="btn btn-primary btn-sm" style="border-radius:8px;font-size:0.78rem;">
            <i class="bi bi-plus-lg me-1"></i> Nuevo producto
        </a>
    </div>
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
            @foreach([
                ['Audífonos Bluetooth','$89.000','15','Activo'],
                ['Mouse Inalámbrico','$45.000','32','Activo'],
                ['Teclado Mecánico','$230.000','8','Activo'],
                ['Webcam HD','$120.000','0','Agotado'],
            ] as $p)
            <tr style="border-color:#f8fafc;">
                <td style="color:#334155;font-weight:500;">{{ $p[0] }}</td>
                <td style="font-weight:600;color:#0f172a;">{{ $p[1] }}</td>
                <td style="color:#64748b;">{{ $p[2] }} unid.</td>
                <td>
                    @if($p[3]=='Activo')
                        <span style="background:#dcfce7;color:#16a34a;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">Activo</span>
                    @else
                        <span style="background:#fee2e2;color:#dc2626;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">Agotado</span>
                    @endif
                </td>
                <td>
                    <a href="#" class="btn btn-sm" style="background:#f1f5f9;color:#475569;border-radius:7px;font-size:0.75rem;padding:4px 10px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection