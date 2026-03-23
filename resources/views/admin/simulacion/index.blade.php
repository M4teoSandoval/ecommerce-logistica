@extends('layouts.app')
@section('title', 'Simulación de Rutas')
@section('page-title', 'Simulación de Rutas')
@section('page-subtitle', 'Calcula la viabilidad de una entrega con dron')
@section('content')

<div class="row g-4">
    <div class="col-md-5">
        <div class="content-card">
            <div style="font-size:0.95rem;font-weight:600;color:#0f172a;margin-bottom:20px;">
                <i class="bi bi-sliders me-2 text-primary"></i>Parámetros de simulación
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li style="font-size:0.82rem;">{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.simulacion.simular') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Seleccionar dron</label>
                    <select name="drone_id" class="form-select" required>
                        <option value="">Selecciona un dron disponible...</option>
                        @foreach($drones as $d)
                            <option value="{{ $d->id }}" {{ old('drone_id')==$d->id ? 'selected':'' }}
                                    data-peso="{{ $d->peso_maximo }}"
                                    data-distancia="{{ $d->distancia_maxima }}"
                                    data-velocidad="{{ $d->velocidad_promedio }}"
                                    data-bateria="{{ $d->capacidad_bateria }}"
                                    data-consumo="{{ $d->consumo_por_km }}">
                                {{ $d->nombre }} — máx. {{ $d->peso_maximo }}kg / {{ $d->distancia_maxima }}km
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="drone-info" class="mb-3 p-3" style="background:#f8fafc;border-radius:10px;display:none;">
                    <div style="font-size:0.75rem;font-weight:600;color:#94a3b8;margin-bottom:8px;">PARÁMETROS DEL DRON</div>
                    <div class="row g-2" id="drone-params"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Distancia de entrega (km)</label>
                    <input type="number" name="distancia" class="form-control" value="{{ old('distancia') }}"
                           placeholder="5.5" step="0.1" min="0.1" max="500" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Peso de la carga (kg)</label>
                    <input type="number" name="peso_carga" class="form-control" value="{{ old('peso_carga') }}"
                           placeholder="1.2" step="0.1" min="0" required>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;padding:12px;font-size:0.9rem;font-weight:600;">
                    <i class="bi bi-play-circle me-2"></i>Ejecutar simulación
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="content-card text-center py-5" id="placeholder-result">
            <div class="stat-icon icon-purple mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
                <i class="bi bi-map"></i>
            </div>
            <div style="font-size:0.95rem;font-weight:600;color:#334155;">Configura los parámetros</div>
            <div style="font-size:0.82rem;color:#94a3b8;margin-top:6px;">
                Selecciona un dron, ingresa la distancia y el peso para simular la ruta
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelector('select[name="drone_id"]').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const info = document.getElementById('drone-info');
    const params = document.getElementById('drone-params');

    if (!this.value) { info.style.display = 'none'; return; }

    const items = [
        ['Peso máx.', opt.dataset.peso + ' kg'],
        ['Distancia', opt.dataset.distancia + ' km'],
        ['Velocidad', opt.dataset.velocidad + ' km/h'],
        ['Batería', opt.dataset.bateria + ' mAh'],
    ];

    params.innerHTML = items.map(i => `
        <div class="col-6">
            <div style="font-size:0.68rem;color:#94a3b8;">${i[0]}</div>
            <div style="font-size:0.82rem;font-weight:600;color:#334155;">${i[1]}</div>
        </div>
    `).join('');

    info.style.display = 'block';
});
</script>
@endpush
@endsection