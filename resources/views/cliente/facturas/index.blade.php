@extends('layouts.app')
@section('title', 'Mis Facturas')
@section('page-title', 'Mis Facturas')
@section('page-subtitle', 'Facturación electrónica')
@section('content')

@if ($facturas->isEmpty())
    <div class="content-card text-center py-5">
        <div class="stat-icon icon-purple mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
            <i class="bi bi-receipt"></i>
        </div>
        <div style="font-size:1rem;font-weight:600;color:#334155;">No tienes facturas</div>
        <div style="font-size:0.82rem;color:#94a3b8;margin-top:6px;">Las facturas se generan automáticamente al confirmar un pedido</div>
        <a href="{{ route('cliente.productos.index') }}" class="btn btn-primary mt-4"
            style="border-radius:10px;padding:10px 24px;">
            <i class="bi bi-search me-2"></i>Ver productos
        </a>
    </div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach ($facturas as $factura)
            <div class="content-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon icon-teal" style="width:42px;height:42px;font-size:1rem;flex-shrink:0;">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div>
                            <div style="font-size:0.9rem;font-weight:700;color:#0f172a;">{{ $factura->numero_factura }}</div>
                            <div style="font-size:0.75rem;color:#94a3b8;">
                                {{ $factura->created_at->format('d/m/Y H:i') }}
                                · Pedido #{{ $factura->pedido_id }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size:1rem;font-weight:700;color:#0f172a;">{{ $factura->total_formateado }}</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('cliente.facturas.show', $factura) }}"
                                class="btn btn-sm"
                                style="border-radius:8px;background:#ede9fe;color:#7c3aed;font-size:0.78rem;padding:6px 14px;border:none;">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                            <a href="{{ route('cliente.facturas.descargar', $factura) }}"
                                class="btn btn-sm"
                                style="border-radius:8px;background:#f1f5f9;color:#475569;font-size:0.78rem;padding:6px 14px;border:none;">
                                <i class="bi bi-download me-1"></i>XML
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
