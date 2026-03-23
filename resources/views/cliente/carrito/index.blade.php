@extends('layouts.app')
@section('title', 'Mi Carrito')
@section('page-title', 'Mi Carrito')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

@if($items->isEmpty())
    <div class="content-card text-center py-5">
        <div class="stat-icon icon-blue mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
            <i class="bi bi-cart3"></i>
        </div>
        <div style="font-size:1rem;font-weight:600;color:#334155;">Tu carrito está vacío</div>
        <div style="font-size:0.82rem;color:#94a3b8;margin-top:6px;">Agrega productos para comenzar</div>
        <a href="{{ route('cliente.productos.index') }}" class="btn btn-primary mt-4" style="border-radius:10px;padding:10px 24px;">
            <i class="bi bi-search me-2"></i>Ver productos
        </a>
    </div>
@else
<div class="row g-4">
    <div class="col-md-8">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:20px;">Productos en tu carrito</div>
            @foreach($items as $item)
            <div class="d-flex align-items-center gap-3 p-3 mb-3" style="background:#f8fafc;border-radius:12px;">
                @if($item->producto->imagen)
                    <img src="{{ asset('storage/'.$item->producto->imagen) }}"
                         style="width:64px;height:64px;object-fit:cover;border-radius:10px;flex-shrink:0;">
                @else
                    <div class="stat-icon icon-purple" style="width:64px;height:64px;font-size:1.4rem;flex-shrink:0;">
                        <i class="bi bi-box"></i>
                    </div>
                @endif
                <div style="flex:1;">
                    <div style="font-size:0.88rem;font-weight:600;color:#334155;">{{ $item->producto->nombre }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">{{ $item->producto->categoria }}</div>
                    <div style="font-size:0.82rem;font-weight:700;color:#6366f1;margin-top:4px;">
                        {{ $item->producto->precio_formateado }}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <form method="POST" action="{{ route('cliente.carrito.actualizar', $item) }}">
                        @csrf @method('PATCH')
                        <div class="d-flex align-items-center gap-1">
                            <button type="submit" name="cantidad" value="{{ max(1, $item->cantidad - 1) }}"
                                    class="btn btn-sm" style="background:#e2e8f0;border-radius:7px;width:30px;height:30px;padding:0;font-size:0.9rem;">−</button>
                            <span style="font-size:0.88rem;font-weight:600;color:#334155;min-width:24px;text-align:center;">{{ $item->cantidad }}</span>
                            <button type="submit" name="cantidad" value="{{ min($item->producto->stock, $item->cantidad + 1) }}"
                                    class="btn btn-sm" style="background:#e2e8f0;border-radius:7px;width:30px;height:30px;padding:0;font-size:0.9rem;">+</button>
                        </div>
                    </form>
                    <div style="font-size:0.88rem;font-weight:700;color:#0f172a;min-width:80px;text-align:right;">
                        ${{ number_format($item->cantidad * $item->producto->precio, 0, ',', '.') }}
                    </div>
                    <form method="POST" action="{{ route('cliente.carrito.eliminar', $item) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:7px;border:none;width:32px;height:32px;padding:0;">
                            <i class="bi bi-trash" style="font-size:0.8rem;"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:20px;">Resumen del pedido</div>
            <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;color:#64748b;">
                <span>Subtotal</span>
                <span>${{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;color:#64748b;">
                <span>Envío</span>
                <span style="color:#16a34a;">Calculado en checkout</span>
            </div>
            <hr style="border-color:#f1f5f9;">
            <div class="d-flex justify-content-between mb-4" style="font-size:1rem;font-weight:700;color:#0f172a;">
                <span>Total estimado</span>
                <span>${{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('cliente.carrito.checkout') }}" class="btn btn-primary w-100" style="border-radius:10px;padding:12px;font-size:0.9rem;font-weight:600;">
                <i class="bi bi-lock me-2"></i>Proceder al pago
            </a>
            <a href="{{ route('cliente.productos.index') }}" class="btn w-100 mt-2" style="border-radius:10px;padding:10px;background:#f1f5f9;color:#475569;font-size:0.85rem;">
                <i class="bi bi-arrow-left me-2"></i>Seguir comprando
            </a>
        </div>
    </div>
</div>
@endif
@endsection