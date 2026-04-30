@extends('layouts.app')
@section('title', 'Entrega #' . $pedido->id)
@section('page-title', 'Entrega #' . $pedido->id)
@section('page-subtitle', 'Detalle de la entrega asignada')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-md-5">
        <div class="content-card mb-4">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">
                <i class="bi bi-info-circle me-2"></i>Info de la entrega
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span style="color:#64748b;font-size:0.82rem;">Estado actual</span>
                <span style="{{ $pedido->estado_color }};padding:5px 14px;border-radius:8px;font-size:0.8rem;font-weight:600;">
                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                </span>
            </div>

            @foreach([
                ['Cliente', $pedido->cliente->name],
                ['Teléfono', $pedido->telefono],
                ['Ciudad', $pedido->ciudad],
                ['Dirección', $pedido->direccion_entrega],
                ['Transporte', ucfirst($pedido->transporte)],
                ['Notas', $pedido->notas ?: 'Sin notas'],
            ] as $d)
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f1f5f9;font-size:0.82rem;">
                <span style="color:#64748b;">{{ $d[0] }}</span>
                <span style="font-weight:600;color:#334155;">{{ $d[1] }}</span>
            </div>
            @endforeach
        </div>

        @if(!empty($estadosDisponibles))
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">
                <i class="bi bi-arrow-up-circle me-2"></i>Actualizar entrega
            </div>
            <form method="POST" action="{{ route('repartidor.entregas.actualizar', $pedido) }}">
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
                           placeholder="Ej: Llegando al punto de entrega">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ubicación (opcional)</label>
                    <input type="text" name="ubicacion_descripcion" class="form-control"
                           placeholder="Ej: Esquina de la calle 50">
                </div>
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label class="form-label">Latitud (opcional)</label>
                        <input type="number" name="latitud" class="form-control"
                               placeholder="7.1198" step="0.0000001">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Longitud (opcional)</label>
                        <input type="number" name="longitud" class="form-control"
                               placeholder="-73.1227" step="0.0000001">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;padding:11px;font-weight:600;">
                    <i class="bi bi-send me-2"></i>Actualizar estado
                </button>
            </form>
        </div>
        @else
        <div class="content-card text-center py-3">
            <div style="font-size:0.85rem;color:#64748b;">
                <i class="bi bi-check-circle me-1"></i>Entrega completada
            </div>
        </div>
        @endif
    </div>

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
