@extends('layouts.app')
@section('title', 'Gestión de Drones')
@section('page-title', 'Gestión de Drones')
@section('page-subtitle', 'Flota de vehículos aéreos')
@section('content')

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    @foreach([
        [$stats['total'],'Total drones','icon-purple','bi-send'],
        [$stats['disponibles'],'Disponibles','icon-green','bi-check-circle'],
        [$stats['mantenimiento'],'En mantenimiento','icon-orange','bi-tools'],
        [$stats['alertas'],'Requieren atención','icon-red','bi-exclamation-triangle'],
    ] as $s)
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon {{ $s[2] }} mb-3"><i class="bi {{ $s[3] }}"></i></div>
            <div style="font-size:1.5rem;font-weight:700;color:#0f172a;">{{ $s[0] }}</div>
            <div style="font-size:0.78rem;color:#94a3b8;margin-top:2px;">{{ $s[1] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="d-flex justify-content-end mb-3 gap-2">
    <a href="{{ route('admin.simulacion.index') }}" class="btn" style="border-radius:10px;background:#f1f5f9;color:#475569;font-size:0.85rem;padding:9px 18px;">
        <i class="bi bi-map me-2"></i>Simular ruta
    </a>
    <a href="{{ route('admin.drones.create') }}" class="btn btn-primary" style="border-radius:10px;font-size:0.85rem;padding:9px 18px;">
        <i class="bi bi-plus-lg me-2"></i>Nuevo dron
    </a>
</div>

<div class="row g-3">
    @forelse($drones as $drone)
    <div class="col-md-4">
        <div class="content-card">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon {{ $drone->necesita_mantenimiento ? 'icon-red' : 'icon-purple' }}" style="width:46px;height:46px;font-size:1.1rem;">
                        <i class="bi bi-send{{ $drone->necesita_mantenimiento ? '-x' : '' }}"></i>
                    </div>
                    <div>
                        <div style="font-size:0.9rem;font-weight:700;color:#0f172a;">{{ $drone->nombre }}</div>
                        <div style="font-size:0.72rem;color:#94a3b8;">{{ $drone->modelo ?? 'Sin modelo' }}</div>
                    </div>
                </div>
                <span style="{{ $drone->estado_color }};padding:3px 10px;border-radius:7px;font-size:0.7rem;font-weight:600;">
                    {{ ucfirst(str_replace('_',' ',$drone->estado)) }}
                </span>
            </div>

            @if($drone->necesita_mantenimiento)
            <div class="mb-3 p-2" style="background:#fef9c3;border-radius:8px;border-left:3px solid #f59e0b;">
                <div style="font-size:0.75rem;color:#854d0e;font-weight:600;">
                    <i class="bi bi-exclamation-triangle me-1"></i>Requiere mantenimiento
                </div>
            </div>
            @endif

            <div class="row g-2 mb-3">
                @foreach([
                    ['Carga máx.', $drone->peso_maximo.' kg', 'bi-box-seam'],
                    ['Distancia', $drone->distancia_maxima.' km', 'bi-arrows-expand'],
                    ['Velocidad', $drone->velocidad_promedio.' km/h', 'bi-speedometer2'],
                    ['Horas vuelo', $drone->horas_vuelo_total.'h', 'bi-clock'],
                ] as $d)
                <div class="col-6">
                    <div style="background:#f8fafc;border-radius:8px;padding:8px 10px;">
                        <div style="font-size:0.65rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;">{{ $d[0] }}</div>
                        <div style="font-size:0.82rem;font-weight:600;color:#334155;margin-top:1px;">{{ $d[1] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-bottom:12px;">
                <div class="d-flex justify-content-between mb-1" style="font-size:0.72rem;color:#94a3b8;">
                    <span>Horas desde mantenimiento</span>
                    <span>{{ $drone->horas_vuelo_desde_mantenimiento }}/{{ $drone->horas_alerta_mantenimiento }}h</span>
                </div>
                <div style="background:#f1f5f9;border-radius:99px;height:6px;">
                    @php $pct = min(100, ($drone->horas_vuelo_desde_mantenimiento / max(1,$drone->horas_alerta_mantenimiento)) * 100) @endphp
                    <div style="background:{{ $pct >= 100 ? '#ef4444' : ($pct >= 75 ? '#f59e0b' : '#6366f1') }};width:{{ $pct }}%;height:6px;border-radius:99px;transition:width 0.3s;"></div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.drones.show', $drone) }}" class="btn btn-sm flex-1" style="background:#f1f5f9;color:#475569;border-radius:8px;font-size:0.78rem;padding:7px;">
                    <i class="bi bi-eye me-1"></i>Ver detalle
                </a>
                <a href="{{ route('admin.drones.edit', $drone) }}" class="btn btn-sm" style="background:#ede9fe;color:#7c3aed;border-radius:8px;font-size:0.78rem;padding:7px 12px;">
                    <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('admin.drones.destroy', $drone) }}" onsubmit="return confirm('¿Eliminar este dron?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:8px;font-size:0.78rem;padding:7px 12px;border:none;">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="content-card text-center py-5">
            <div class="stat-icon icon-purple mx-auto mb-3" style="width:56px;height:56px;font-size:1.5rem;">
                <i class="bi bi-send"></i>
            </div>
            <div style="font-size:0.95rem;font-weight:600;color:#334155;">No hay drones registrados</div>
            <a href="{{ route('admin.drones.create') }}" class="btn btn-primary mt-3" style="border-radius:10px;">
                <i class="bi bi-plus-lg me-2"></i>Registrar primer dron
            </a>
        </div>
    </div>
    @endforelse
</div>
@endsection