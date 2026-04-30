@extends('layouts.app')
@section('title', 'Pedido #' . $pedido->id)
@section('page-title', 'Pedido #' . $pedido->id)
@section('page-subtitle', 'Detalle y gestión del pedido')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="row g-4">
    {{-- Columna izquierda: Info del pedido --}}
    <div class="col-md-5">
        <div class="content-card mb-4">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">
                <i class="bi bi-info-circle me-2"></i>Información del pedido
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span style="color:#64748b;font-size:0.82rem;">Estado actual</span>
                <span style="{{ $pedido->estado_color }};padding:5px 14px;border-radius:8px;font-size:0.8rem;font-weight:600;">
                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                </span>
            </div>

            @foreach([
                ['Cliente', $pedido->cliente->name],
                ['Email', $pedido->cliente->email],
                ['Teléfono', $pedido->telefono],
                ['Ciudad', $pedido->ciudad],
                ['Dirección', $pedido->direccion_entrega],
                ['Transporte', ucfirst($pedido->transporte)],
                ['Fecha', $pedido->created_at->format('d/m/Y H:i')],
            ] as $d)
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f1f5f9;font-size:0.82rem;">
                <span style="color:#64748b;">{{ $d[0] }}</span>
                <span style="font-weight:600;color:#334155;">{{ $d[1] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Mis productos en este pedido --}}
        <div class="content-card mb-4">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">
                <i class="bi bi-box-seam me-2"></i>Tus productos en este pedido
            </div>
            @foreach($itemsProveedor as $item)
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f5f9;font-size:0.82rem;">
                <div>
                    <div style="font-weight:600;color:#334155;">{{ $item->producto->nombre ?? 'Producto eliminado' }}</div>
                    <div style="font-size:0.72rem;color:#94a3b8;">{{ $item->cantidad }} x {{ '$' . number_format($item->precio_unitario, 0, ',', '.') }}</div>
                </div>
                <div style="font-weight:700;color:#0f172a;">{{ '$' . number_format($item->subtotal, 0, ',', '.') }}</div>
            </div>
            @endforeach
            <div class="d-flex justify-content-between py-2 mt-2" style="border-top:2px solid #e2e8f0;font-size:0.82rem;">
                <span style="font-weight:700;color:#0f172a;">Subtotal tus productos</span>
                <span style="font-weight:700;color:#0f172a;">{{ '$' . number_format($itemsProveedor->sum('subtotal'), 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Actualizar estado --}}
        @if(!empty($estadosDisponibles))
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">
                <i class="bi bi-arrow-up-circle me-2"></i>Avanzar estado del pedido
            </div>
            <form method="POST" action="{{ route('proveedor.pedidos.actualizar', $pedido) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nuevo estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="">Seleccionar estado...</option>
                        @foreach($estadosDisponibles as $e)
                        <option value="{{ $e }}">{{ ucfirst(str_replace('_', ' ', $e)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción (opcional)</label>
                    <input type="text" name="descripcion" class="form-control"
                           placeholder="Ej: Pedido confirmado y en preparación">
                </div>
                <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;padding:11px;font-weight:600;">
                    <i class="bi bi-send me-2"></i>Actualizar estado
                </button>
            </form>
        </div>
        @else
        <div class="content-card text-center py-3">
            <div style="font-size:0.85rem;color:#64748b;">
                <i class="bi bi-check-circle me-1"></i>Este pedido ya fue enviado a logística
            </div>
        </div>
        @endif
    </div>

    {{-- Columna derecha: Historial de seguimiento --}}
    <div class="col-md-7">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:20px;">
                <i class="bi bi-clock-history me-2"></i>Historial de seguimiento
            </div>

            @if($pedido->seguimientos->isEmpty())
            <div class="text-center py-4" style="color:#94a3b8;font-size:0.85rem;">
                Sin actualizaciones aún
            </div>
            @else
            <div style="position:relative;padding-left:28px;">
                @foreach($pedido->seguimientos->reverse() as $s)
                <div style="position:relative;padding-bottom:20px;">
                    <div style="position:absolute;left:-28px;top:0;width:18px;height:18px;border-radius:50%;background:{{ $s->color }};display:flex;align-items:center;justify-content:center;">
                        <i class="bi {{ $s->icono }}" style="font-size:9px;color:white;"></i>
                    </div>
                    @if(!$loop->last)
                    <div style="position:absolute;left:-20px;top:18px;width:2px;height:calc(100% - 18px);background:#f1f5f9;"></div>
                    @endif
                    <div style="background:#f8fafc;border-radius:10px;padding:12px 16px;margin-left:8px;">
                        <div class="d-flex justify-content-between">
                            <div style="font-size:0.85rem;font-weight:600;color:#334155;">{{ $s->descripcion }}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;">{{ $s->created_at->format('d/m H:i') }}</div>
                        </div>
                        @if($s->ubicacion_descripcion)
                        <div style="font-size:0.72rem;color:#94a3b8;margin-top:3px;">
                            <i class="bi bi-geo-alt me-1"></i>{{ $s->ubicacion_descripcion }}
                        </div>
                        @endif
                        <span style="background:#f1f5f9;color:#64748b;padding:2px 8px;border-radius:5px;font-size:0.68rem;font-weight:600;display:inline-block;margin-top:5px;">
                            {{ ucfirst(str_replace('_', ' ', $s->estado)) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
