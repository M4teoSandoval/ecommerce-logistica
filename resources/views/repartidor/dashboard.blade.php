@extends('layouts.app')
@section('title', 'Panel Repartidor')
@section('page-title', 'Mis Entregas')
@section('page-subtitle', 'Resumen del día')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-blue mb-3"><i class="bi bi-calendar-check"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $entregasHoy }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">Entregas hoy</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-green mb-3"><i class="bi bi-bag-check"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $entregasCompletadasHoy }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">Completadas hoy</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-orange mb-3"><i class="bi bi-truck"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $enCamino }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">En camino</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-purple mb-3"><i class="bi bi-hourglass-split"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $pendientes }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">Pendientes</div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;">Entregas activas</div>
            <div style="font-size:0.75rem;color:#94a3b8;">Pedidos asignados pendientes de entrega</div>
        </div>
        <a href="{{ route('repartidor.entregas.index') }}" class="btn btn-primary btn-sm" style="border-radius:8px;font-size:0.78rem;">
            <i class="bi bi-list-ul me-1"></i> Ver todas
        </a>
    </div>

    @if($entregasRecientes->isEmpty())
    <div class="text-center py-4" style="color:#94a3b8;font-size:0.85rem;">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        No tienes entregas asignadas por el momento
    </div>
    @else
    <div class="d-flex flex-column gap-3">
        @foreach($entregasRecientes as $pedido)
        <div class="d-flex align-items-center justify-content-between p-3" style="background:#f8fafc;border-radius:12px;border:1px solid #f1f5f9;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon icon-blue" style="width:42px;height:42px;font-size:1rem;flex-shrink:0;">
                    <i class="bi {{ $pedido->transporte_icon }}"></i>
                </div>
                <div>
                    <div style="font-size:0.9rem;font-weight:700;color:#0f172a;">Pedido #{{ $pedido->id }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">
                        <i class="bi bi-geo-alt me-1"></i>{{ $pedido->ciudad }} · {{ $pedido->cliente->name }}
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span style="{{ $pedido->estado_color }};padding:4px 12px;border-radius:8px;font-size:0.75rem;font-weight:600;">
                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                </span>
                <a href="{{ route('repartidor.entregas.show', $pedido) }}"
                   class="btn btn-sm"
                   style="border-radius:8px;background:#ede9fe;color:#7c3aed;font-size:0.78rem;padding:6px 14px;border:none;">
                    <i class="bi bi-eye me-1"></i>Ver
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
