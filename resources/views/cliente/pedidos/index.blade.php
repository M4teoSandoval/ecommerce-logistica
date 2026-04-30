@extends('layouts.app')
@section('title', 'Mis Pedidos')
@section('page-title', 'Mis Pedidos')
@section('page-subtitle', 'Historial de compras')
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
        <div class="d-flex flex-column gap-3">
            @foreach ($pedidos as $pedido)
                <div class="content-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon icon-purple" style="width:42px;height:42px;font-size:1rem;flex-shrink:0;">
                                <i class="bi {{ $pedido->transporte_icon }}"></i>
                            </div>
                            <div>
                                <div style="font-size:0.9rem;font-weight:700;color:#0f172a;">Pedido #{{ $pedido->id }}
                                </div>
                                <div style="font-size:0.75rem;color:#94a3b8;">{{ $pedido->created_at->format('d/m/Y H:i') }}
                                    · {{ ucfirst($pedido->transporte) }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span
                                style="{{ $pedido->estado_color }};padding:4px 12px;border-radius:8px;font-size:0.75rem;font-weight:600;">
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
                            @endif
                            <div style="font-size:1rem;font-weight:700;color:#0f172a;">{{ $pedido->total_formateado }}</div>
                        </div>
                    </div>

                    <div style="border-top:1px solid #f1f5f9;padding-top:12px;">
                        <div style="font-size:0.75rem;color:#94a3b8;margin-bottom:8px;">Productos</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($pedido->items as $item)
                                <span
                                    style="background:#f8fafc;border:1px solid #f1f5f9;padding:4px 10px;border-radius:8px;font-size:0.78rem;color:#475569;">
                                    {{ $item->producto->nombre ?? 'Producto eliminado' }} x{{ $item->cantidad }}
                                </span>
                            @endforeach
                        </div>
                        <div style="font-size:0.75rem;color:#94a3b8;margin-top:10px;">
                            <i class="bi bi-geo-alt me-1"></i>{{ $pedido->direccion_entrega }}, {{ $pedido->ciudad }}
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('cliente.seguimiento.index', $pedido) }}" class="btn btn-sm"
                            style="border-radius:8px;background:#ede9fe;color:#7c3aed;font-size:0.78rem;padding:6px 14px;border:none;">
                            <i class="bi bi-geo-alt me-1"></i>Ver seguimiento
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
