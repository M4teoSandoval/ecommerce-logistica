@extends('layouts.app')
@section('title', 'Checkout')
@section('page-title', 'Finalizar compra')
@section('page-subtitle', 'Completa tu información de envío')
@section('content')

<div class="row g-4">
    <div class="col-md-7">
        <div class="content-card">
            <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                Información de entrega
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e)
                            <li style="font-size:0.82rem;">{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('cliente.carrito.procesar') }}">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label">Dirección de entrega</label>
                        <input type="text" name="direccion_entrega" class="form-control"
                               placeholder="Calle 123 # 45-67, Apto 8" value="{{ old('direccion_entrega') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ciudad</label>
                        <input type="text" name="ciudad" class="form-control"
                               placeholder="Bucaramanga" value="{{ old('ciudad') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono de contacto</label>
                        <input type="text" name="telefono" class="form-control"
                               placeholder="300 000 0000" value="{{ old('telefono', Auth::user()->phone) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notas adicionales</label>
                        <textarea name="notas" class="form-control" rows="2"
                                  placeholder="Instrucciones especiales para la entrega...">{{ old('notas') }}</textarea>
                    </div>
                </div>

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                    Medio de transporte
                </div>

                <div class="row g-3 mb-4">
                    @foreach([
                        ['dron','Dron','Entregas rápidas hasta 2kg','bi-send','$8.000','icon-purple'],
                        ['moto','Moto','Entregas locales económicas','bi-bicycle','$5.000','icon-blue'],
                        ['furgoneta','Furgoneta','Paquetes grandes o pesados','bi-truck','$12.000','icon-green'],
                    ] as $t)
                    <div class="col-md-4">
                        <label style="cursor:pointer;display:block;">
                            <input type="radio" name="transporte" value="{{ $t[0] }}"
                                   {{ old('transporte','moto')==$t[0] ? 'checked':'' }}
                                   class="d-none transport-radio" data-costo="{{ $t[4] }}">
                            <div class="transport-card p-3 text-center" style="border:2px solid #e2e8f0;border-radius:12px;transition:all 0.2s;">
                                <div class="stat-icon {{ $t[5] }} mx-auto mb-2" style="width:44px;height:44px;font-size:1.1rem;">
                                    <i class="bi {{ $t[2] }}"></i>
                                </div>
                                <div style="font-size:0.85rem;font-weight:600;color:#334155;">{{ $t[1] }}</div>
                                <div style="font-size:0.7rem;color:#94a3b8;margin-top:2px;">{{ $t[2] }}</div>
                                <div style="font-size:0.88rem;font-weight:700;color:#6366f1;margin-top:6px;">{{ $t[4] }}</div>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;padding:13px;font-size:0.92rem;font-weight:600;">
                    <i class="bi bi-check-circle me-2"></i>Confirmar pedido
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-5">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">Tu pedido</div>
            @foreach($items as $item)
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon icon-purple" style="width:40px;height:40px;font-size:0.9rem;flex-shrink:0;">
                    <i class="bi bi-box"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.82rem;font-weight:600;color:#334155;">{{ Str::limit($item->producto->nombre, 30) }}</div>
                    <div style="font-size:0.72rem;color:#94a3b8;">x{{ $item->cantidad }}</div>
                </div>
                <div style="font-size:0.85rem;font-weight:700;color:#0f172a;">
                    ${{ number_format($item->cantidad * $item->producto->precio, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
            <hr style="border-color:#f1f5f9;">
            <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;color:#64748b;">
                <span>Subtotal</span>
                <span>${{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;color:#64748b;">
                <span>Envío</span>
                <span id="costo-envio">$5.000</span>
            </div>
            <hr style="border-color:#f1f5f9;">
            <div class="d-flex justify-content-between" style="font-size:1rem;font-weight:700;color:#0f172a;">
                <span>Total</span>
                <span id="total-final">${{ number_format($subtotal + 5000, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const subtotal = {{ $subtotal }};
const costos = { dron: 8000, moto: 5000, furgoneta: 12000 };

function formatNum(n) {
    return '$' + n.toLocaleString('es-CO');
}

function updateTotal(transporte) {
    const costo = costos[transporte] || 5000;
    document.getElementById('costo-envio').textContent = formatNum(costo);
    document.getElementById('total-final').textContent = formatNum(subtotal + costo);

    document.querySelectorAll('.transport-card').forEach(c => {
        c.style.borderColor = '#e2e8f0';
        c.style.background = '#fff';
    });
    const selected = document.querySelector(`input[value="${transporte}"]`);
    if (selected) {
        const card = selected.nextElementSibling;
        card.style.borderColor = '#6366f1';
        card.style.background = '#f5f3ff';
    }
}

document.querySelectorAll('.transport-radio').forEach(radio => {
    radio.addEventListener('change', () => updateTotal(radio.value));
});

updateTotal('{{ old('transporte', 'moto') }}');
</script>
@endpush
@endsection