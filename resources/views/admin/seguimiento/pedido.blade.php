@extends('layouts.app')
@section('title', 'Gestionar Pedido')
@section('page-title', 'Gestionar Pedido')
@section('page-subtitle', 'Actualiza el estado del envío')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-md-5">
        <div class="content-card mb-4">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">
                <i class="bi bi-plus-circle me-2"></i>Agregar actualización
            </div>

            <form method="POST" action="{{ route('admin.seguimiento.actualizar', $pedido) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nuevo estado</label>
                    <select name="estado" class="form-select" required>
                        @foreach([
                            'pendiente'  => 'Pendiente',
                            'confirmado' => 'Confirmado',
                            'preparando' => 'Preparando',
                            'en_camino'  => 'En camino',
                            'cerca'      => 'Cerca del destino',
                            'entregado'  => 'Entregado',
                            'cancelado'  => 'Cancelado',
                        ] as $val => $label)
                        <option value="{{ $val }}" {{ $pedido->estado == $val ? 'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control"
                           placeholder="Ej: Paquete recogido por el repartidor" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ubicación (descripción)</label>
                    <input type="text" name="ubicacion_descripcion" class="form-control"
                           placeholder="Ej: Centro de distribución norte">
                </div>
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label class="form-label">Latitud</label>
                        <input type="number" name="latitud" class="form-control"
                               placeholder="7.1198" step="0.0000001">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Longitud</label>
                        <input type="number" name="longitud" class="form-control"
                               placeholder="-73.1227" step="0.0000001">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;padding:11px;font-weight:600;">
                    <i class="bi bi-send me-2"></i>Publicar actualización
                </button>
            </form>
        </div>

        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">Info del pedido</div>
            @foreach([
                ['Cliente', $pedido->cliente->name],
                ['Teléfono', $pedido->telefono],
                ['Ciudad', $pedido->ciudad],
                ['Dirección', $pedido->direccion_entrega],
                ['Transporte', ucfirst($pedido->transporte)],
                ['Total', $pedido->total_formateado],
            ] as $d)
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f1f5f9;font-size:0.82rem;">
                <span style="color:#64748b;">{{ $d[0] }}</span>
                <span style="font-weight:600;color:#334155;">{{ $d[1] }}</span>
            </div>
            @endforeach
        </div>

        <div class="content-card mt-4">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:16px;">
                <i class="bi bi-bicycle me-2"></i>Repartidor asignado
            </div>
            @if($pedido->repartidor)
                <div class="d-flex align-items-center gap-2 p-3" style="background:#ffedd5;border-radius:10px;">
                    <i class="bi bi-person-badge" style="font-size:1.2rem;color:#ea580c;"></i>
                    <div>
                        <div style="font-weight:600;color:#9a3412;">{{ $pedido->repartidor->name }}</div>
                        <div style="font-size:0.75rem;color:#c2410c;">{{ $pedido->repartidor->email }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.seguimiento.asignar', $pedido) }}" class="ms-auto">
                        @csrf
                        <input type="hidden" name="repartidor_id" value="">
                        <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:6px;font-size:0.7rem;padding:4px 8px;">
                            <i class="bi bi-x"></i> Quitar
                        </button>
                    </form>
                </div>
            @else
                <form method="POST" action="{{ route('admin.seguimiento.asignar', $pedido) }}">
                    @csrf
                    <select name="repartidor_id" class="form-select mb-2" required>
                        <option value="">Seleccionar repartidor...</option>
                        @foreach($repartidores as $r)
                        <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->email }})</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm" style="background:#ffedd5;color:#ea580c;border-radius:8px;font-size:0.78rem;padding:6px 14px;">
                        <i class="bi bi-person-plus me-1"></i>Asignar repartidor
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="col-md-7">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:20px;">
                Historial de seguimiento
            </div>

            @if($pedido->seguimientos->isEmpty())
            <div class="text-center py-4" style="color:#94a3b8;font-size:0.85rem;">
                Sin actualizaciones aún. Agrega la primera.
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
                        <span style="{{ $s->tipo_color ?? 'background:#f1f5f9;color:#64748b' }};padding:2px 8px;border-radius:5px;font-size:0.68rem;font-weight:600;display:inline-block;margin-top:5px;">
                            {{ ucfirst(str_replace('_',' ',$s->estado)) }}
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