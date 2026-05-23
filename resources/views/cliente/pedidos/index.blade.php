@extends('layouts.app')
@section('title', 'Mis Pedidos')
@section('page-title', 'Mis Pedidos')
@section('page-subtitle', 'Historial de compras')
@push('styles')
<style>
    .filter-tab {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 500;
        color: #64748b;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .filter-tab:hover { background: #f1f5f9; }
    .filter-tab.active { background: #ede9fe; color: #7c3aed; font-weight: 600; }

    .progress-steps {
        display: flex;
        align-items: center;
        gap: 0;
        margin-top: 12px;
    }
    .progress-step {
        display: flex;
        align-items: center;
        font-size: 0.7rem;
        color: #94a3b8;
    }
    .progress-step .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #e2e8f0;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }
    .progress-step .dot.done { background: #16a34a; }
    .progress-step .dot.active { background: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
    .progress-step .dot.cancelled { background: #ef4444; }
    .progress-step .line {
        width: 20px;
        height: 2px;
        background: #e2e8f0;
        flex-shrink: 0;
    }
    .progress-step .line.done { background: #16a34a; }

    .timeline-sm {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }
    .timeline-sm .step {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.65rem;
        color: #94a3b8;
    }
    .timeline-sm .step i { font-size: 0.6rem; }
    .timeline-sm .step.done { color: #16a34a; }
    .timeline-sm .step.current { color: #6366f1; font-weight: 600; }
    .timeline-sm .step.cancelled { color: #ef4444; }
    .timeline-sm .connector { color: #e2e8f0; font-size: 0.5rem; }
</style>
@endpush
@section('content')

@if (session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
@endif

@if ($pedidos->isEmpty())
    <div class="content-card text-center py-5">
        <div class="stat-icon icon-purple mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
            <i class="bi bi-bag"></i>
        </div>
        <div style="font-size:1rem;font-weight:600;color:#334155;">Aún no tienes pedidos</div>
        <div style="font-size:0.82rem;color:#94a3b8;margin-top:6px;">Realiza tu primera compra</div>
        <a href="{{ route('cliente.productos.index') }}" class="btn btn-primary mt-4"
            style="border-radius:10px;padding:10px 24px;">
            <i class="bi bi-search me-2"></i>Ver productos
        </a>
    </div>
@else
    @php
        $estados = ['todos', 'pendiente', 'confirmado', 'en_camino', 'entregado', 'cancelado'];
        $currentFilter = request('estado', 'todos');
    @endphp

    <div class="content-card mb-3">
        <div class="d-flex gap-2 overflow-auto" style="scrollbar-width:none;">
            @foreach ($estados as $estado)
                @php
                    $count = $estado === 'todos' ? $pedidos->count() : $pedidos->where('estado', $estado)->count();
                    $label = match($estado) {
                        'todos'     => 'Todos',
                        'pendiente' => 'Pendientes',
                        'confirmado'=> 'Confirmados',
                        'en_camino' => 'En camino',
                        'entregado' => 'Entregados',
                        'cancelado' => 'Cancelados',
                        default     => $estado,
                    };
                @endphp
                <a href="{{ $estado === 'todos' ? route('cliente.pedidos.index') : route('cliente.pedidos.index', ['estado' => $estado]) }}"
                    class="filter-tab {{ $currentFilter === $estado ? 'active' : '' }}">
                    {{ $label }} ({{ $count }})
                </a>
            @endforeach
        </div>
    </div>

    @php
        $filtered = $currentFilter === 'todos'
            ? $pedidos
            : $pedidos->where('estado', $currentFilter);
    @endphp

    @if ($filtered->isEmpty())
        <div class="content-card text-center py-4">
            <div style="font-size:0.9rem;color:#64748b;">No hay pedidos en esta categoría</div>
        </div>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach ($filtered as $pedido)
                @php
                    $ordenEstados = ['pendiente', 'confirmado', 'en_camino', 'entregado'];
                    $idxActual = array_search($pedido->estado, $ordenEstados);
                    $cancelable = in_array($pedido->estado, ['pendiente', 'confirmado']);
                    if ($pedido->stripe_payment_status === 'paid' && $pedido->estado === 'confirmado') {
                        $minutos = \Carbon\Carbon::parse($pedido->created_at)->diffInMinutes(now());
                        $cancelable = $cancelable && $minutos <= 30;
                    }
                    $puedeCancelar = $cancelable;
                @endphp
                <div class="content-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon icon-purple" style="width:42px;height:42px;font-size:1rem;flex-shrink:0;">
                                <i class="bi {{ $pedido->transporte_icon }}"></i>
                            </div>
                            <div>
                                <div style="font-size:0.9rem;font-weight:700;color:#0f172a;">Pedido #{{ $pedido->id }}</div>
                                <div style="font-size:0.75rem;color:#94a3b8;">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                    · {{ ucfirst($pedido->transporte) }}
                                    @if($pedido->canceled_at)
                                        · Cancelado {{ \Carbon\Carbon::parse($pedido->canceled_at)->format('d/m/Y H:i') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="{{ $pedido->estado_color }};padding:4px 12px;border-radius:8px;font-size:0.75rem;font-weight:600;">
                                {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                            </span>
                            @if($pedido->stripe_payment_status === 'paid')
                                <span style="background:#dcfce7;color:#16a34a;padding:4px 12px;border-radius:8px;font-size:0.75rem;font-weight:600;">
                                    <i class="bi bi-check-circle me-1"></i>Pagado
                                </span>
                            @elseif($pedido->stripe_payment_status === 'pending')
                                <span style="background:#fef3c7;color:#d97706;padding:4px 12px;border-radius:8px;font-size:0.75rem;font-weight:600;">
                                    <i class="bi bi-clock me-1"></i>Pago pendiente
                                </span>
                            @elseif($pedido->stripe_payment_status === 'refunded')
                                <span style="background:#fee2e2;color:#dc2626;padding:4px 12px;border-radius:8px;font-size:0.75rem;font-weight:600;">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reembolsado
                                </span>
                            @endif
                            <div style="font-size:1rem;font-weight:700;color:#0f172a;">{{ $pedido->total_formateado }}</div>
                        </div>
                    </div>

                    @if($pedido->estado !== 'cancelado')
                    <div class="timeline-sm mb-3">
                        @foreach($ordenEstados as $i => $e)
                            @php
                                $nombre = match($e) {
                                    'pendiente' => 'Pendiente',
                                    'confirmado' => 'Confirmado',
                                    'en_camino' => 'En camino',
                                    'entregado' => 'Entregado',
                                    default => $e,
                                };
                                $clase = $i < $idxActual ? 'done' : ($i === $idxActual ? 'current' : '');
                            @endphp
                            <div class="step {{ $clase }}">
                                <i class="bi {{ match($e) {
                                    'pendiente' => 'bi-circle',
                                    'confirmado' => 'bi-check-circle',
                                    'en_camino' => 'bi-truck',
                                    'entregado' => 'bi-bag-check',
                                    default => 'bi-circle'
                                } }}"></i>
                                <span>{{ $nombre }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="connector"><i class="bi bi-chevron-right"></i></div>
                            @endif
                        @endforeach
                    </div>
                    @endif

                    <div style="border-top:1px solid #f1f5f9;padding-top:12px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div style="font-size:0.75rem;color:#94a3b8;margin-bottom:8px;">Productos</div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($pedido->items as $item)
                                        <span style="background:#f8fafc;border:1px solid #f1f5f9;padding:4px 10px;border-radius:8px;font-size:0.78rem;color:#475569;">
                                            {{ $item->producto->nombre ?? 'Producto eliminado' }} x{{ $item->cantidad }}
                                        </span>
                                    @endforeach
                                </div>
                                <div style="font-size:0.75rem;color:#94a3b8;margin-top:10px;">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $pedido->direccion_entrega }}, {{ $pedido->ciudad }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('cliente.seguimiento.index', $pedido) }}" class="btn btn-sm"
                            style="border-radius:8px;background:#ede9fe;color:#7c3aed;font-size:0.78rem;padding:6px 14px;border:none;">
                            <i class="bi bi-geo-alt me-1"></i>Ver seguimiento
                        </a>
                        @if($pedido->factura)
                            <a href="{{ route('cliente.facturas.show', $pedido->factura) }}" class="btn btn-sm"
                                style="border-radius:8px;background:#ccfbf1;color:#0d9488;font-size:0.78rem;padding:6px 14px;border:none;">
                                <i class="bi bi-receipt me-1"></i>Ver factura
                            </a>
                        @endif
                        @if($puedeCancelar)
                            <button type="button" class="btn btn-sm ms-auto"
                                style="border-radius:8px;background:#fee2e2;color:#dc2626;font-size:0.78rem;padding:6px 14px;border:none;"
                                data-bs-toggle="modal" data-bs-target="#cancelModal{{ $pedido->id }}">
                                <i class="bi bi-x-circle me-1"></i>Cancelar pedido
                            </button>

                            <div class="modal fade" id="cancelModal{{ $pedido->id }}" tabindex="-1">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content" style="border-radius:14px;border:none;padding:8px;">
                                        <div class="modal-body text-center py-4">
                                            <div class="stat-icon icon-red mx-auto mb-3" style="width:56px;height:56px;font-size:1.4rem;">
                                                <i class="bi bi-exclamation-triangle"></i>
                                            </div>
                                            <div style="font-size:0.95rem;font-weight:700;color:#0f172a;">Cancelar pedido</div>
                                            <div style="font-size:0.8rem;color:#64748b;margin-top:6px;">
                                                ¿Estás seguro de cancelar el pedido #{{ $pedido->id }}?
                                                @if($pedido->stripe_payment_status === 'paid')
                                                    <br>Se simulará el reembolso del pago.
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 px-4 pb-4">
                                            <button type="button" class="btn flex-fill" data-bs-dismiss="modal"
                                                style="border-radius:10px;background:#f1f5f9;color:#475569;border:none;padding:10px;">
                                                No, volver
                                            </button>
                                            <form action="{{ route('cliente.pedidos.cancelar', $pedido) }}" method="POST" class="flex-fill">
                                                @csrf
                                                <button type="submit" class="btn w-100"
                                                    style="border-radius:10px;background:#dc2626;color:white;border:none;padding:10px;font-weight:600;">
                                                    <i class="bi bi-check-circle me-1"></i>Sí, cancelar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endif
@endsection
