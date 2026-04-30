@extends('layouts.app')
@section('title', 'Mis Entregas')
@section('page-title', 'Mis Entregas')
@section('page-subtitle', 'Pedidos asignados para entrega')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

{{-- Filtros --}}
<div class="content-card mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('repartidor.entregas.index') }}"
           class="btn btn-sm {{ !$estado ? 'btn-primary' : '' }}"
           style="border-radius:8px;font-size:0.78rem;padding:6px 14px;">
            Todos <span class="badge bg-white bg-opacity-25 ms-1">{{ $counters['todos'] }}</span>
        </a>
        @foreach([
            'preparando' => 'Preparando',
            'en_camino' => 'En camino',
            'cerca' => 'Cerca',
            'entregado' => 'Entregado',
        ] as $val => $label)
            <a href="{{ route('repartidor.entregas.index', ['estado' => $val]) }}"
               class="btn btn-sm {{ $estado === $val ? 'btn-primary' : '' }}"
               style="border-radius:8px;font-size:0.78rem;padding:6px 14px;{{ $estado !== $val ? 'background:#f1f5f9;color:#475569;border:none;' : '' }}">
                {{ $label }} <span class="badge {{ $estado === $val ? 'bg-white bg-opacity-25' : 'bg-secondary' }} ms-1">{{ $counters[$val] ?? 0 }}</span>
            </a>
        @endforeach
    </div>
</div>

{{-- Listado --}}
@if($entregas->isEmpty())
    <div class="content-card text-center py-5">
        <div class="stat-icon icon-blue mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
            <i class="bi bi-bicycle"></i>
        </div>
        <div style="font-size:1rem;font-weight:600;color:#334155;">No hay entregas{{ $estado ? ' con este filtro' : '' }}</div>
        <div style="font-size:0.82rem;color:#94a3b8;margin-top:6px;">Las entregas asignadas por el admin aparecerán aquí</div>
    </div>
@else
    <div class="content-card">
        <div class="table-responsive">
            <table class="table align-middle" style="font-size:0.83rem;">
                <thead>
                    <tr style="color:#94a3b8;">
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Pedido</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Cliente</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Transporte</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Destino</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Estado</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Última actualización</th>
                        <th style="font-weight:500;border:none;padding-bottom:14px;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entregas as $pedido)
                    <tr style="border-color:#f8fafc;">
                        <td style="font-weight:700;color:#6366f1;">#{{ $pedido->id }}</td>
                        <td>
                            <div style="font-weight:600;color:#334155;">{{ $pedido->cliente->name }}</div>
                            <div style="font-size:0.72rem;color:#94a3b8;">{{ $pedido->telefono }}</div>
                        </td>
                        <td>
                            <span style="background:#ede9fe;color:#7c3aed;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                                <i class="bi {{ $pedido->transporte_icon }} me-1"></i>{{ ucfirst($pedido->transporte) }}
                            </span>
                        </td>
                        <td style="color:#475569;font-size:0.8rem;">
                            {{ $pedido->ciudad }}<br>
                            <span style="font-size:0.72rem;color:#94a3b8;">{{ Str::limit($pedido->direccion_entrega, 25) }}</span>
                        </td>
                        <td>
                            <span style="{{ $pedido->estado_color }};padding:3px 10px;border-radius:7px;font-size:0.72rem;font-weight:600;">
                                {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                            </span>
                        </td>
                        <td>
                            @if($pedido->ultimoSeguimiento)
                                <div style="font-size:0.78rem;color:#334155;">{{ $pedido->ultimoSeguimiento->descripcion }}</div>
                                <div style="font-size:0.7rem;color:#94a3b8;">
                                    {{ $pedido->ultimoSeguimiento->created_at->diffForHumans() }}
                                </div>
                            @else
                                <span style="color:#94a3b8;font-size:0.75rem;">Sin seguimiento</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('repartidor.entregas.show', $pedido) }}"
                               class="btn btn-sm btn-primary" style="border-radius:8px;font-size:0.75rem;padding:6px 12px;">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $entregas->links() }}</div>
@endif

@endsection
