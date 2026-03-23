@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general del sistema')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-purple"><i class="bi bi-people"></i></div>
                <span class="text-success" style="font-size:0.75rem; font-weight:600;">+12%</span>
            </div>
            <div style="font-size:1.6rem; font-weight:700; color:#0f172a;">124</div>
            <div style="font-size:0.78rem; color:#94a3b8; margin-top:2px;">Usuarios activos</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-blue"><i class="bi bi-send"></i></div>
                <span class="text-success" style="font-size:0.75rem; font-weight:600;">+5%</span>
            </div>
            <div style="font-size:1.6rem; font-weight:700; color:#0f172a;">8</div>
            <div style="font-size:0.78rem; color:#94a3b8; margin-top:2px;">Drones activos</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-green"><i class="bi bi-bag-check"></i></div>
                <span class="text-success" style="font-size:0.75rem; font-weight:600;">+28%</span>
            </div>
            <div style="font-size:1.6rem; font-weight:700; color:#0f172a;">342</div>
            <div style="font-size:0.78rem; color:#94a3b8; margin-top:2px;">Pedidos este mes</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-orange"><i class="bi bi-currency-dollar"></i></div>
                <span class="text-success" style="font-size:0.75rem; font-weight:600;">+18%</span>
            </div>
            <div style="font-size:1.6rem; font-weight:700; color:#0f172a;">$4.2M</div>
            <div style="font-size:0.78rem; color:#94a3b8; margin-top:2px;">Ingresos del mes</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div style="font-size:0.95rem; font-weight:600; color:#0f172a;">Pedidos recientes</div>
                    <div style="font-size:0.75rem; color:#94a3b8;">Últimas transacciones</div>
                </div>
                <a href="#" class="btn btn-primary btn-sm" style="border-radius:8px; font-size:0.78rem;">Ver todos</a>
            </div>
            <table class="table" style="font-size:0.82rem;">
                <thead>
                    <tr style="color:#94a3b8; border-bottom: 1px solid #f1f5f9;">
                        <th class="fw-500 pb-3" style="font-weight:500; border:none;">ID</th>
                        <th class="fw-500 pb-3" style="font-weight:500; border:none;">Cliente</th>
                        <th class="fw-500 pb-3" style="font-weight:500; border:none;">Transporte</th>
                        <th class="fw-500 pb-3" style="font-weight:500; border:none;">Estado</th>
                        <th class="fw-500 pb-3" style="font-weight:500; border:none;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['#001','María García','Dron','Entregado','$45.000'],
                        ['#002','Carlos López','Moto','En camino','$120.000'],
                        ['#003','Ana Martínez','Furgoneta','Pendiente','$280.000'],
                        ['#004','Luis Pérez','Dron','Entregado','$35.000'],
                    ] as $pedido)
                    <tr style="border-color:#f8fafc;">
                        <td style="color:#6366f1; font-weight:600;">{{ $pedido[0] }}</td>
                        <td style="color:#334155;">{{ $pedido[1] }}</td>
                        <td>
                            @if($pedido[2] == 'Dron')
                                <span style="background:#ede9fe;color:#7c3aed;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                                    <i class="bi bi-send"></i> Dron
                                </span>
                            @elseif($pedido[2] == 'Moto')
                                <span style="background:#dbeafe;color:#1d4ed8;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                                    <i class="bi bi-bicycle"></i> Moto
                                </span>
                            @else
                                <span style="background:#dcfce7;color:#16a34a;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                                    <i class="bi bi-truck"></i> Furgoneta
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($pedido[3] == 'Entregado')
                                <span style="background:#dcfce7;color:#16a34a;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">Entregado</span>
                            @elseif($pedido[3] == 'En camino')
                                <span style="background:#fef9c3;color:#854d0e;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">En camino</span>
                            @else
                                <span style="background:#f1f5f9;color:#64748b;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">Pendiente</span>
                            @endif
                        </td>
                        <td style="font-weight:600; color:#0f172a;">{{ $pedido[4] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card h-100">
            <div style="font-size:0.95rem; font-weight:600; color:#0f172a; margin-bottom:4px;">Estado de flota</div>
            <div style="font-size:0.75rem; color:#94a3b8; margin-bottom:20px;">Vehículos disponibles</div>
            @foreach([
                ['Drones','8 disponibles','2 en mantenimiento','icon-purple','bi-send'],
                ['Motos','5 disponibles','1 en ruta','icon-blue','bi-bicycle'],
                ['Furgonetas','3 disponibles','0 en ruta','icon-green','bi-truck'],
            ] as $v)
            <div class="d-flex align-items-center gap-3 mb-3 p-3" style="background:#f8fafc;border-radius:10px;">
                <div class="stat-icon {{ $v[2] }}" style="width:38px;height:38px;font-size:1rem;flex-shrink:0;">
                    <i class="bi {{ $v[4] }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.82rem;font-weight:600;color:#334155;">{{ $v[0] }}</div>
                    <div style="font-size:0.72rem;color:#16a34a;">{{ $v[1] }}</div>
                    <div style="font-size:0.72rem;color:#94a3b8;">{{ $v[2] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection