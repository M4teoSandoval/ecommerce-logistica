@extends('layouts.app')
@section('title', 'Seguimiento del Pedido')
@section('page-title', 'Seguimiento en tiempo real')
@section('page-subtitle', 'Pedido #{{ $pedido->id }}')
@section('content')

<div class="row g-4">
    <div class="col-md-7">
        <div class="content-card mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <div style="font-size:0.95rem;font-weight:600;color:#0f172a;">Estado del pedido</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">Actualización automática cada 10 segundos</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div id="pulse" style="width:8px;height:8px;background:#16a34a;border-radius:50%;animation:pulse 1.5s infinite;"></div>
                    <span style="font-size:0.75rem;color:#16a34a;font-weight:600;">En vivo</span>
                </div>
            </div>

            <div id="timeline" style="position:relative;padding-left:28px;">
                @foreach($pedido->seguimientos as $i => $s)
                <div class="timeline-item" style="position:relative;padding-bottom:24px;">
                    <div style="position:absolute;left:-28px;top:0;width:18px;height:18px;border-radius:50%;background:{{ $s->color }};display:flex;align-items:center;justify-content:center;z-index:1;">
                        <i class="bi {{ $s->icono }}" style="font-size:9px;color:white;"></i>
                    </div>
                    @if(!$loop->last)
                    <div style="position:absolute;left:-20px;top:18px;width:2px;height:calc(100% - 18px);background:#f1f5f9;"></div>
                    @endif
                    <div style="background:#f8fafc;border-radius:10px;padding:12px 16px;margin-left:8px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="font-size:0.85rem;font-weight:600;color:#334155;">{{ $s->descripcion }}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;white-space:nowrap;margin-left:12px;">{{ $s->created_at->format('H:i') }}</div>
                        </div>
                        @if($s->ubicacion_descripcion)
                        <div style="font-size:0.72rem;color:#94a3b8;margin-top:4px;">
                            <i class="bi bi-geo-alt me-1"></i>{{ $s->ubicacion_descripcion }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($pedido->seguimientos->isEmpty())
                <div class="text-center py-3" style="color:#94a3b8;font-size:0.85rem;">
                    Tu pedido está siendo procesado...
                </div>
                @endif
            </div>
        </div>

        @if($pedido->ultimoSeguimiento?->latitud)
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:12px;">
                <i class="bi bi-map me-2"></i>Ubicación actual
            </div>
            <div id="mapa" style="width:100%;height:280px;border-radius:12px;background:#f8fafc;display:flex;align-items:center;justify-content:center;">
                <iframe
                    width="100%" height="280"
                    style="border:none;border-radius:12px;"
                    src="https://maps.google.com/maps?q={{ $pedido->ultimoSeguimiento->latitud }},{{ $pedido->ultimoSeguimiento->longitud }}&z=15&output=embed">
                </iframe>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-5">
        <div class="content-card mb-4">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">Resumen del pedido</div>

            <div id="estado-badge" class="text-center mb-4">
                <span style="{{ $pedido->estado_color }};padding:8px 20px;border-radius:10px;font-size:0.85rem;font-weight:700;display:inline-block;">
                    {{ ucfirst(str_replace('_',' ',$pedido->estado)) }}
                </span>
            </div>

            @foreach([
                ['Pedido', '#'.$pedido->id],
                ['Transporte', ucfirst($pedido->transporte)],
                ['Dirección', $pedido->direccion_entrega.', '.$pedido->ciudad],
                ['Total', $pedido->total_formateado],
                ['Fecha', $pedido->created_at->format('d/m/Y H:i')],
            ] as $d)
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f1f5f9;font-size:0.83rem;">
                <span style="color:#64748b;">{{ $d[0] }}</span>
                <span style="font-weight:600;color:#334155;">{{ $d[1] }}</span>
            </div>
            @endforeach
        </div>

        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">Productos</div>
            @foreach($pedido->items as $item)
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon icon-purple" style="width:38px;height:38px;font-size:0.9rem;flex-shrink:0;">
                    <i class="bi bi-box"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.82rem;font-weight:600;color:#334155;">{{ $item->producto->nombre ?? 'Producto' }}</div>
                    <div style="font-size:0.72rem;color:#94a3b8;">x{{ $item->cantidad }}</div>
                </div>
                <div style="font-size:0.82rem;font-weight:700;color:#0f172a;">
                    ${{ number_format($item->subtotal, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('styles')
<style>
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
}
</style>
@endpush

@push('scripts')
<script>
const pedidoId = {{ $pedido->id }};
const estadoUrl = "{{ route('cliente.seguimiento.estado', $pedido) }}";

function actualizarTimeline(data) {
    if (!data.seguimientos.length) return;

    const timeline = document.getElementById('timeline');
    timeline.innerHTML = data.seguimientos.map((s, i) => `
        <div class="timeline-item" style="position:relative;padding-bottom:24px;">
            <div style="position:absolute;left:-28px;top:0;width:18px;height:18px;border-radius:50%;background:${s.color};display:flex;align-items:center;justify-content:center;z-index:1;">
                <i class="bi ${s.icono}" style="font-size:9px;color:white;"></i>
            </div>
            ${i < data.seguimientos.length - 1 ? '<div style="position:absolute;left:-20px;top:18px;width:2px;height:calc(100% - 18px);background:#f1f5f9;"></div>' : ''}
            <div style="background:#f8fafc;border-radius:10px;padding:12px 16px;margin-left:8px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div style="font-size:0.85rem;font-weight:600;color:#334155;">${s.descripcion}</div>
                    <div style="font-size:0.7rem;color:#94a3b8;white-space:nowrap;margin-left:12px;">${s.hora}</div>
                </div>
                ${s.ubicacion ? `<div style="font-size:0.72rem;color:#94a3b8;margin-top:4px;"><i class="bi bi-geo-alt me-1"></i>${s.ubicacion}</div>` : ''}
            </div>
        </div>
    `).join('');
}

async function fetchEstado() {
    try {
        const res = await fetch(estadoUrl);
        const data = await res.json();
        actualizarTimeline(data);
    } catch(e) {
        console.error('Error al actualizar:', e);
    }
}

setInterval(fetchEstado, 10000);
</script>
@endpush
@endsection