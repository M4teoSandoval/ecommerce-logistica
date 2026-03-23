@extends('layouts.app')
@section('title', 'Seguimiento de Pedidos')
@section('page-title', 'Seguimiento de Pedidos')
@section('page-subtitle', 'Pedidos activos en el sistema')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="content-card">
    <div class="table-responsive">
        <table class="table align-middle" style="font-size:0.83rem;">
            <thead>
                <tr style="color:#94a3b8;">
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Pedido</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Cliente</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Transporte</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Destino</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Último estado</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Total</th>
                    <th style="font-weight:500;border:none;padding-bottom:14px;">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                <tr style="border-color:#f8fafc;">
                    <td style="font-weight:700;color:#6366f1;">#{{ $pedido->id }}</td>
                    <td>
                        <div style="font-weight:600;color:#334155;">{{ $pedido->cliente->name }}</div>
                        <div style="font-size:0.72rem;color:#94a3b8;">{{ $pedido->cliente->email }}</div>
                    </td>
                    <td>
                        <span style="background:#ede9fe;color:#7c3aed;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                            <i class="bi {{ $pedido->transporte_icon }} me-1"></i>{{ ucfirst($pedido->transporte) }}
                        </span>
                    </td>
                    <td style="color:#475569;font-size:0.8rem;">
                        {{ $pedido->ciudad }}<br>
                        <span style="font-size:0.72rem;color:#94a3b8;">{{ Str::limit($pedido->direccion_entrega, 30) }}</span>
                    </td>
                    <td>
                        @if($pedido->ultimoSeguimiento)
                            <span style="{{ $pedido->estado_color }};padding:3px 10px;border-radius:7px;font-size:0.72rem;font-weight:600;">
                                {{ ucfirst(str_replace('_',' ',$pedido->estado)) }}
                            </span>
                            <div style="font-size:0.7rem;color:#94a3b8;margin-top:3px;">
                                {{ $pedido->ultimoSeguimiento->created_at->diffForHumans() }}
                            </div>
                        @else
                            <span style="background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:7px;font-size:0.72rem;font-weight:600;">Sin estado</span>
                        @endif
                    </td>
                    <td style="font-weight:700;color:#0f172a;">{{ $pedido->total_formateado }}</td>
                    <td>
                        <a href="{{ route('admin.seguimiento.pedido', $pedido) }}"
                           class="btn btn-sm btn-primary" style="border-radius:8px;font-size:0.75rem;padding:6px 12px;">
                            <i class="bi bi-pencil me-1"></i>Actualizar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4" style="color:#94a3b8;">No hay pedidos activos</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pedidos->links() }}</div>
</div>
@endsection