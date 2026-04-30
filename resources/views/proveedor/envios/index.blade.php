@extends('layouts.app')
@section('title', 'Envíos')
@section('page-title', 'Envíos')
@section('page-subtitle', 'Seguimiento de envíos en curso')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

{{-- Resumen --}}
<div class="row g-3 mb-4">
    <div class="col-md-6 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-orange mb-2"><i class="bi bi-box-seam"></i></div>
            <div style="font-size:1.4rem;font-weight:700;color:#0f172a;">{{ $enPreparacion }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;">En preparación</div>
        </div>
    </div>
    <div class="col-md-6 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-blue mb-2"><i class="bi bi-truck"></i></div>
            <div style="font-size:1.4rem;font-weight:700;color:#0f172a;">{{ $enCamino }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;">En camino a logística</div>
        </div>
    </div>
</div>

{{-- Listado de envíos --}}
@if($envios->isEmpty())
    <div class="content-card text-center py-5">
        <div class="stat-icon icon-blue mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
            <i class="bi bi-truck"></i>
        </div>
        <div style="font-size:1rem;font-weight:600;color:#334155;">No hay envíos activos</div>
        <div style="font-size:0.82rem;color:#94a3b8;margin-top:6px;">Los envíos en preparación o en camino aparecerán aquí</div>
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
                    @foreach($envios as $pedido)
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
                            <a href="{{ route('proveedor.pedidos.show', $pedido) }}"
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
@endif

@endsection
