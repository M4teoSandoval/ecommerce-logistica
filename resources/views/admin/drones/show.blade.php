@extends('layouts.app')
@section('title', $drone->nombre)
@section('page-title', $drone->nombre)
@section('page-subtitle', 'Detalle y historial de mantenimiento')
@section('content')

<div class="row g-4">
    <div class="col-md-4">
        <div class="content-card">
            <div class="text-center mb-4">
                <div class="stat-icon {{ $drone->necesita_mantenimiento ? 'icon-red' : 'icon-purple' }} mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
                    <i class="bi bi-send"></i>
                </div>
                <div style="font-size:1rem;font-weight:700;color:#0f172a;">{{ $drone->nombre }}</div>
                <div style="font-size:0.78rem;color:#94a3b8;">{{ $drone->modelo ?? 'Sin modelo' }}</div>
                <span style="{{ $drone->estado_color }};padding:4px 12px;border-radius:8px;font-size:0.75rem;font-weight:600;display:inline-block;margin-top:8px;">
                    {{ ucfirst(str_replace('_',' ',$drone->estado)) }}
                </span>
            </div>

            @if($drone->necesita_mantenimiento)
            <div class="mb-4 p-3" style="background:#fef9c3;border-radius:10px;border-left:3px solid #f59e0b;">
                <div style="font-size:0.8rem;color:#854d0e;font-weight:600;">
                    <i class="bi bi-exclamation-triangle me-2"></i>Requiere mantenimiento preventivo
                </div>
                <div style="font-size:0.72rem;color:#92400e;margin-top:4px;">
                    Ha superado las {{ $drone->horas_alerta_mantenimiento }} horas de vuelo desde el último mantenimiento.
                </div>
            </div>
            @endif

            @foreach([
                ['Peso máx. carga', $drone->peso_maximo.' kg'],
                ['Distancia máxima', $drone->distancia_maxima.' km'],
                ['Altitud máxima', $drone->altitud_maxima.' m'],
                ['Velocidad promedio', $drone->velocidad_promedio.' km/h'],
                ['Capacidad batería', $drone->capacidad_bateria.' mAh'],
                ['Consumo por km', $drone->consumo_por_km.' mAh'],
                ['Horas vuelo total', $drone->horas_vuelo_total.'h'],
            ] as $p)
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f1f5f9;font-size:0.82rem;">
                <span style="color:#64748b;">{{ $p[0] }}</span>
                <span style="font-weight:600;color:#334155;">{{ $p[1] }}</span>
            </div>
            @endforeach

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.drones.edit', $drone) }}" class="btn btn-primary flex-1" style="border-radius:9px;font-size:0.82rem;">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <a href="{{ route('admin.mantenimientos.create') }}?drone_id={{ $drone->id }}" class="btn flex-1" style="border-radius:9px;font-size:0.82rem;background:#fef9c3;color:#854d0e;border:none;">
                    <i class="bi bi-tools me-1"></i>Mantenimiento
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:20px;">Historial de mantenimiento</div>
            @forelse($mantenimientos as $m)
            <div class="d-flex gap-3 mb-3 p-3" style="background:#f8fafc;border-radius:10px;">
                <div class="stat-icon {{ $m->tipo == 'incidente' ? 'icon-red' : ($m->tipo == 'correctivo' ? 'icon-orange' : 'icon-green') }}" style="width:40px;height:40px;font-size:0.9rem;flex-shrink:0;">
                    <i class="bi bi-tools"></i>
                </div>
                <div style="flex:1;">
                    <div class="d-flex justify-content-between">
                        <div style="font-size:0.85rem;font-weight:600;color:#334155;">{{ $m->descripcion }}</div>
                        <span style="{{ $m->tipo_color }};padding:2px 8px;border-radius:6px;font-size:0.7rem;font-weight:600;">{{ ucfirst($m->tipo) }}</span>
                    </div>
                    <div style="font-size:0.72rem;color:#94a3b8;margin-top:4px;">
                        {{ $m->fecha->format('d/m/Y') }}
                        @if($m->tecnico) · Técnico: {{ $m->tecnico }} @endif
                        @if($m->costo) · Costo: ${{ number_format($m->costo, 0, ',', '.') }} @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.mantenimientos.update', $m) }}">
                    @csrf @method('PATCH')
                    <select name="estado" class="form-select form-select-sm" style="font-size:0.75rem;border-radius:7px;width:110px;"
                            onchange="this.form.submit()">
                        @foreach(['programado','en_proceso','completado'] as $e)
                            <option value="{{ $e }}" {{ $m->estado == $e ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            @empty
            <div class="text-center py-4" style="color:#94a3b8;font-size:0.85rem;">
                Sin registros de mantenimiento aún
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection