@extends('layouts.app')
@section('title', $producto->nombre)
@section('page-title', $producto->nombre)
@section('page-subtitle', $producto->categoria)
@section('content')

<div class="row g-4">
    <div class="col-md-5">
        <div class="content-card p-0 overflow-hidden position-relative" style="background:#f8fafc;">
            @if ($producto->imagen)
                <div class="overflow-hidden" style="cursor:zoom-in;">
                    <img src="{{ route('imagenes.servir', ['path' => $producto->imagen]) }}"
                         alt="{{ $producto->nombre }}"
                         loading="lazy"
                         class="w-100"
                         style="height:380px;object-fit:contain;transition:transform 0.4s;display:block;"
                         onmouseover="this.style.transform='scale(1.15)'"
                         onmouseout="this.style.transform='scale(1)'">
                </div>
            @else
                <div style="width:100%;height:380px;background:linear-gradient(135deg,#ede9fe,#dbeafe);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-box" style="font-size:4rem;color:#94a3b8;"></i>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-7">
        <div class="content-card h-100 d-flex flex-column">
            <div>
                <span style="background:#ede9fe;color:#7c3aed;padding:3px 10px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                    {{ $producto->categoria }}
                </span>
                <h4 style="font-weight:700;color:#0f172a;margin-top:12px;margin-bottom:4px;">{{ $producto->nombre }}</h4>
                <div style="font-size:0.82rem;color:#94a3b8;margin-bottom:12px;">
                    Vendido por <span style="color:#6366f1;font-weight:600;">{{ $producto->proveedor->name }}</span>
                </div>

                <div style="font-size:2rem;font-weight:700;color:#0f172a;margin-bottom:16px;">
                    {{ $producto->precio_formateado }}
                </div>

                @if($producto->descripcion)
                    <p style="font-size:0.875rem;color:#475569;line-height:1.7;margin-bottom:20px;">
                        {{ $producto->descripcion }}
                    </p>
                @endif
            </div>

            <div class="row g-2 mb-4">
                @foreach ([
                    ['Peso', $producto->peso . ' kg', 'bi-box-seam'],
                    ['Stock', $producto->stock > 0 ? $producto->stock . ' unidades' : 'Agotado', 'bi-layers'],
                    ['Alto', $producto->alto ? $producto->alto . ' cm' : '—', 'bi-arrows-vertical'],
                    ['Ancho', $producto->ancho ? $producto->ancho . ' cm' : '—', 'bi-arrows'],
                ] as $d)
                    <div class="col-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:12px 14px;">
                            <div style="font-size:0.7rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">
                                <i class="bi {{ $d[2] }} me-1"></i>{{ $d[0] }}
                            </div>
                            <div style="font-size:0.88rem;font-weight:600;color:#334155;margin-top:2px;">
                                {{ $d[1] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-auto">
                @if ($producto->stock > 0)
                    <form method="POST" action="{{ route('cliente.carrito.agregar', $producto) }}" id="addToCartForm">
                        @csrf
                        <div class="d-flex gap-2 mb-3">
                            <div class="d-flex align-items-center gap-1" style="background:#f1f5f9;border-radius:10px;padding:4px;">
                                <button type="button" class="btn btn-sm" style="border:none;background:transparent;color:#475569;font-size:1.1rem;width:36px;height:36px;padding:0;" onclick="decrementQty()">−</button>
                                <input type="number" name="cantidad" id="qtyInput" value="1" min="1" max="{{ $producto->stock }}"
                                    class="form-control" style="width:50px;border-radius:6px;text-align:center;border:none;background:white;padding:4px;font-weight:600;">
                                <button type="button" class="btn btn-sm" style="border:none;background:transparent;color:#475569;font-size:1.1rem;width:36px;height:36px;padding:0;" onclick="incrementQty({{ $producto->stock }})">+</button>
                            </div>
                            <button type="submit" class="btn btn-primary flex-fill" id="addBtn"
                                style="border-radius:10px;padding:10px 20px;font-size:0.9rem;font-weight:600;">
                                <i class="bi bi-cart-plus me-2"></i>Agregar al carrito
                            </button>
                        </div>
                    </form>
                @else
                    <button class="btn w-100 disabled"
                        style="border-radius:10px;padding:12px;background:#fee2e2;color:#dc2626;font-size:0.9rem;font-weight:600;border:none;">
                        <i class="bi bi-x-circle me-2"></i>Producto agotado
                    </button>
                @endif

                <a href="{{ route('cliente.productos.index') }}" class="btn w-100 mt-2"
                    style="border-radius:10px;padding:10px;background:#f1f5f9;color:#475569;font-size:0.85rem;">
                    <i class="bi bi-arrow-left me-2"></i>Volver al catálogo
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function decrementQty() {
    const input = document.getElementById('qtyInput');
    const val = parseInt(input.value) || 1;
    if (val > 1) input.value = val - 1;
}
function incrementQty(max) {
    const input = document.getElementById('qtyInput');
    const val = parseInt(input.value) || 1;
    if (val < max) input.value = val + 1;
}
document.getElementById('addToCartForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('addBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Agregando...';
});
</script>
@endpush
@endsection
