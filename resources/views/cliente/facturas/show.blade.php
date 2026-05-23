@extends('layouts.app')
@section('title', 'Factura ' . $factura->numero_factura)
@section('page-title', 'Factura ' . $factura->numero_factura)
@section('page-subtitle', 'Detalle de factura electrónica')
@section('content')

<div class="content-card" style="max-width:800px;margin:0 auto;">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div style="font-size:1.3rem;font-weight:800;color:#0f172a;letter-spacing:-0.02em;">
                <span style="color:#6366f1;">Swift</span>Drop
            </div>
            <div style="font-size:0.75rem;color:#94a3b8;">Sistema de comercio y logística</div>
        </div>
        <div class="text-end">
            <div style="font-size:1.1rem;font-weight:700;color:#0f172a;">{{ $factura->numero_factura }}</div>
            <div style="font-size:0.75rem;color:#94a3b8;">{{ $factura->created_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div style="border-top:2px solid #f1f5f9;border-bottom:2px solid #f1f5f9;padding:16px 0;margin-bottom:20px;">
        <div class="row g-3">
            <div class="col-6">
                <div style="font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;">Emisor</div>
                <div style="font-size:0.85rem;font-weight:600;color:#0f172a;margin-top:4px;">SwiftDrop S.A.S.</div>
                <div style="font-size:0.78rem;color:#64748b;">NIT 900.123.456-7</div>
                <div style="font-size:0.78rem;color:#64748b;">Cra 45 # 67-89, Bogotá D.C.</div>
            </div>
            <div class="col-6">
                <div style="font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;">Adquiriente</div>
                <div style="font-size:0.85rem;font-weight:600;color:#0f172a;margin-top:4px;">{{ $factura->cliente->name }}</div>
                <div style="font-size:0.78rem;color:#64748b;">CC {{ $factura->cliente->id }}</div>
                <div style="font-size:0.78rem;color:#64748b;">{{ $factura->cliente->email }}</div>
            </div>
        </div>
    </div>

    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;">
        <thead>
            <tr style="border-bottom:1px solid #f1f5f9;">
                <th style="text-align:left;padding:8px 12px;font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;">Producto</th>
                <th style="text-align:center;padding:8px 12px;font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;">Cant</th>
                <th style="text-align:right;padding:8px 12px;font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;">Precio</th>
                <th style="text-align:right;padding:8px 12px;font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factura->pedido->items as $item)
                <tr style="border-bottom:1px solid #f8fafc;">
                    <td style="padding:10px 12px;font-size:0.82rem;color:#334155;">{{ $item->producto->nombre ?? 'Producto eliminado' }}</td>
                    <td style="text-align:center;padding:10px 12px;font-size:0.82rem;color:#64748b;">{{ $item->cantidad }}</td>
                    <td style="text-align:right;padding:10px 12px;font-size:0.82rem;color:#64748b;">${{ number_format($item->precio_unitario, 0, ',', '.') }}</td>
                    <td style="text-align:right;padding:10px 12px;font-size:0.82rem;font-weight:600;color:#0f172a;">${{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-left:auto;width:280px;">
        <div class="d-flex justify-content-between py-1">
            <span style="font-size:0.82rem;color:#64748b;">Subtotal</span>
            <span style="font-size:0.82rem;color:#0f172a;">{{ $factura->subtotal_formateado }}</span>
        </div>
        <div class="d-flex justify-content-between py-1">
            <span style="font-size:0.82rem;color:#64748b;">IVA (19%)</span>
            <span style="font-size:0.82rem;color:#0f172a;">{{ $factura->iva_formateado }}</span>
        </div>
        <div class="d-flex justify-content-between py-1" style="border-top:2px solid #f1f5f9;margin-top:4px;padding-top:8px;">
            <span style="font-size:1rem;font-weight:700;color:#0f172a;">Total</span>
            <span style="font-size:1rem;font-weight:700;color:#6366f1;">{{ $factura->total_formateado }}</span>
        </div>
    </div>

    <div style="border-top:2px solid #f1f5f9;margin-top:24px;padding-top:16px;">
        <div style="font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Documento electrónico</div>
        <div style="background:#f8fafc;border-radius:8px;padding:12px;font-size:0.72rem;color:#64748b;font-family:monospace;word-break:break-all;max-height:80px;overflow-y:auto;">
            CUFE: {{ 'simulado_cufe_' . $factura->numero_factura . '_' . $factura->pedido_id . '_' . $factura->total }}
        </div>
        <div style="margin-top:8px;display:flex;gap:8px;align-items:center;">
            <span style="background:#dcfce7;color:#16a34a;padding:2px 8px;border-radius:5px;font-size:0.68rem;font-weight:600;">
                <i class="bi bi-check-circle me-1"></i>Documento válido
            </span>
            <span style="font-size:0.7rem;color:#94a3b8;">DIAN — Ambiente de pruebas</span>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('cliente.facturas.descargar', $factura) }}" class="btn btn-primary"
            style="border-radius:10px;padding:10px 20px;">
            <i class="bi bi-download me-2"></i>Descargar XML
        </a>
        <a href="{{ route('cliente.facturas.index') }}" class="btn"
            style="border-radius:10px;padding:10px 20px;background:#f1f5f9;color:#475569;border:none;">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>
@endsection
