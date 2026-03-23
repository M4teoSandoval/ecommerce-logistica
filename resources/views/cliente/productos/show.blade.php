@extends('layouts.app')
@section('title', $producto->nombre)
@section('page-title', $producto->nombre)
@section('page-subtitle', $producto->categoria)
@section('content')

<div class="row g-4">
    <div class="col-md-5">
        <div class="content-card p-0 overflow-hidden">
            @if($producto->imagen)
                <img src="{{ asset('storage/'.$producto->imagen) }}"
                     style="width:100%;max-height:380px;object-fit:cover;">
            @else
                <div style="width:100%;height:380px;background:linear-gradient(135deg,#ede9fe,#dbeafe);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-box" style="font-size:4rem;color:#94a3b8;"></i>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-7">
        <div class="content-card">
            <span style="background:#f1f5f9;color:#475569;padding:3px 10px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                {{ $producto->categoria }}
            </span>
            <h4 style="font-weight:700;color:#0f172a;margin-top:12px;margin-bottom:6px;">{{ $producto->nombre }}</h4>
            <div style="font-size:0.82rem;color:#94a3b8;margin-bottom:16px;">
                Vendido por <span style="color:#6366f1;font-weight:600;">{{ $producto->proveedor->name }}</span>
            </div>

            <div style="font-size:1.8rem;font-weight:700;color:#0f172a;margin-bottom:16px;">
                {{ $producto->precio_formateado }}
            </div>

            <p style="font-size:0.875rem;color:#475569;line-height:1.7;margin-bottom:20px;">
                {{ $producto->descripcion }}
            </p>

            <div class="row g-2 mb-4">
                @foreach([
                    ['Peso', $producto->peso.' kg', 'bi-box-seam'],
                    ['Stock', $producto->stock.' unidades', 'bi-layers'],
                    ['Alto', $producto->alto ? $producto->alto.' cm' : 'N/A', 'bi-arrows-vertical'],
                    ['Ancho', $producto->ancho ? $producto->ancho.' cm' : 'N/A', 'bi-arrows'],
                ] as $d)
                <div class="col-6">
                    <div style="background:#f8fafc;border-radius:10px;padding:12px 14px;">
                        <div style="font-size:0.7rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">{{ $d[0] }}</div>
                        <div style="font-size:0.88rem;font-weight:600;color:#334155;margin-top:2px;">{{ $d[1] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($producto->stock > 0)
                <button class="btn btn-primary w-100" style="border-radius:10px;padding:12px;font-size:0.9rem;font-weight:600;">
                    <i class="bi bi-cart-plus me-2"></i>Agregar al carrito
                </button>
            @else
                <button class="btn w-100 disabled" style="border-radius:10px;padding:12px;background:#fee2e2;color:#dc2626;font-size:0.9rem;font-weight:600;border:none;">
                    <i class="bi bi-x-circle me-2"></i>Producto agotado
                </button>
            @endif

            <a href="{{ route('cliente.productos.index') }}" class="btn w-100 mt-2" style="border-radius:10px;padding:10px;background:#f1f5f9;color:#475569;font-size:0.85rem;">
                <i class="bi bi-arrow-left me-2"></i>Volver al catálogo
            </a>
        </div>
    </div>
</div>
@endsection