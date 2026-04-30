@extends('layouts.app')
@section('title', 'Pedidos')
@section('page-title', 'Pedidos')
@section('page-subtitle', 'Gestiona los pedidos de tus productos')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

{{-- Contadores --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-purple mb-2"><i class="bi bi-bag"></i></div>
            <div style="font-size:1.4rem;font-weight:700;color:#0f172a;">{{ $counters['todos'] }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;">Total pedidos</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-orange mb-2"><i class="bi bi-clock"></i></div>
            <div style="font-size:1.4rem;font-weight:700;color:#0f172a;">{{ $counters['pendiente'] }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;">Pendientes</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-blue mb-2"><i class="bi bi-hourglass-split"></i></div>
            <div style="font-size:1.4rem;font-weight:700;color:#0f172a;">{{ $counters['confirmado'] }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;">Confirmados</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-green mb-2"><i class="bi bi-truck"></i></div>
            <div style="font-size:1.4rem;font-weight:700;color:#0f172a;">{{ $counters['en_camino'] }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;">En camino</div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="content-card mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('proveedor.pedidos.index') }}"
           class="btn btn-sm {{ !$estado ? 'btn-primary' : '' }}"
           style="border-radius:8px;font-size:0.78rem;padding:6px 14px;">
            Todos <span class="badge bg-white bg-opacity-25 ms-1">{{ $counters['todos'] }}</span>
        </a>
        @foreach([
            'pendiente' => 'Pendiente',
            'confirmado' => 'Confirmado',
            'preparando' => 'Preparando',
            'en_camino' => 'En camino',
            'entregado' => 'Entregado',
        ] as $val => $label)
            <a href="{{ route('proveedor.pedidos.index', ['estado' => $val]) }}"
               class="btn btn-sm {{ $estado === $val ? 'btn-primary' : '' }}"
               style="border-radius:8px;font-size:0.78rem;padding:6px 14px;{{ $estado !== $val ? 'background:#f1f5f9;color:#475569;border:none;' : '' }}">
                {{ $label }} <span class="badge {{ $estado === $val ? 'bg-white bg-opacity-25' : 'bg-secondary' }} ms-1">{{ $counters[$val] ?? 0 }}</span>
            </a>
        @endforeach
    </div>
</div>

{{-- Listado --}}
@if($pedidos->isEmpty())
    <div class="content-card text-center py-5">
        <div class="stat-icon icon-purple mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
            <i class="bi bi-bag"></i>
        </div>
        <div style="font-size:1rem;font-weight:600;color:#334155;">No hay pedidos{{ $estado ? ' con este filtro' : '' }}</div>
        <div style="font-size:0.82rem;color:#94a3b8;margin-top:6px;">Los pedidos de tus productos aparecerán aquí</div>
    </div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach($pedidos as $pedido)
            <div class="content-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon icon-purple" style="width:42px;height:42px;font-size:1rem;flex-shrink:0;">
                            <i class="bi {{ $pedido->transporte_icon }}"></i>
                        </div>
                        <div>
                            <a href="{{ route('proveedor.pedidos.show', $pedido) }}" style="font-size:0.9rem;font-weight:700;color:#0f172a;text-decoration:none;">
                                Pedido #{{ $pedido->id }}
                            </a>
                            <div style="font-size:0.75rem;color:#94a3b8;">
                                {{ $pedido->created_at->format('d/m/Y H:i') }} · {{ ucfirst($pedido->transporte) }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span style="{{ $pedido->estado_color }};padding:4px 12px;border-radius:8px;font-size:0.75rem;font-weight:600;">
                            {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                        </span>
                        <div style="font-size:1rem;font-weight:700;color:#0f172a;">{{ $pedido->total_formateado }}</div>
                    </div>
                </div>

                <div style="border-top:1px solid #f1f5f9;padding-top:12px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-wrap gap-2">
                            <div style="font-size:0.75rem;color:#94a3b8;">
                                <i class="bi bi-person me-1"></i>{{ $pedido->cliente->name }}
                            </div>
                            <div style="font-size:0.75rem;color:#94a3b8;">
                                <i class="bi bi-geo-alt me-1"></i>{{ $pedido->ciudad }}
                            </div>
                        </div>
                        <a href="{{ route('proveedor.pedidos.show', $pedido) }}"
                           class="btn btn-sm"
                           style="border-radius:8px;background:#ede9fe;color:#7c3aed;font-size:0.78rem;padding:6px 14px;border:none;">
                            <i class="bi bi-eye me-1"></i>Ver detalle
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $pedidos->links() }}</div>
@endif

@endsection
