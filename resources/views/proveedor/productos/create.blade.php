@extends('layouts.app')
@section('title', 'Nuevo Producto')
@section('page-title', 'Nuevo Producto')
@section('page-subtitle', 'Completa los datos del producto')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="content-card">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e)
                            <li style="font-size:0.82rem;">{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('proveedor.productos.store') }}" enctype="multipart/form-data">
                @csrf

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                    Información básica
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label">Nombre del producto</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Ej: Audífonos Bluetooth Pro" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe tu producto..." required>{{ old('descripcion') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio (COP)</label>
                        <input type="number" name="precio" class="form-control" value="{{ old('precio') }}" placeholder="89000" min="0" step="100" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock disponible</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" placeholder="0" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select" required>
                            <option value="">Selecciona...</option>
                            @foreach(['Electrónica','Ropa','Hogar','Alimentos','Deportes','Libros','Juguetes','Salud','Otros'] as $cat)
                                <option value="{{ $cat }}" {{ old('categoria')==$cat ? 'selected':'' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                    Dimensiones y peso
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" name="peso" class="form-control" value="{{ old('peso') }}" placeholder="0.5" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Alto (cm)</label>
                        <input type="number" name="alto" class="form-control" value="{{ old('alto') }}" placeholder="10" min="0" step="0.1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ancho (cm)</label>
                        <input type="number" name="ancho" class="form-control" value="{{ old('ancho') }}" placeholder="15" min="0" step="0.1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Largo (cm)</label>
                        <input type="number" name="largo" class="form-control" value="{{ old('largo') }}" placeholder="20" min="0" step="0.1">
                    </div>
                </div>

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                    Imagen del producto
                </div>

                <div class="mb-4">
                    <div id="drop-zone" style="border:2px dashed #e2e8f0;border-radius:12px;padding:30px;text-align:center;cursor:pointer;transition:border-color 0.2s;"
                         onclick="document.getElementById('imagen').click()"
                         ondragover="event.preventDefault();this.style.borderColor='#6366f1'"
                         ondragleave="this.style.borderColor='#e2e8f0'"
                         ondrop="handleDrop(event)">
                        <i class="bi bi-cloud-upload" style="font-size:2rem;color:#94a3b8;"></i>
                        <div style="font-size:0.85rem;color:#64748b;margin-top:8px;">Arrastra una imagen o <span style="color:#6366f1;font-weight:600;">selecciona un archivo</span></div>
                        <div style="font-size:0.72rem;color:#94a3b8;margin-top:4px;">PNG, JPG hasta 2MB</div>
                        <div id="file-name" style="font-size:0.8rem;color:#6366f1;margin-top:8px;font-weight:500;"></div>
                    </div>
                    <input type="file" id="imagen" name="imagen" accept="image/*" class="d-none"
                           onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? ''">
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('proveedor.productos.index') }}" class="btn" style="border-radius:10px;background:#f1f5f9;color:#475569;font-size:0.85rem;padding:9px 18px;">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-size:0.85rem;padding:9px 18px;">
                        <i class="bi bi-check-lg me-2"></i>Guardar producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleDrop(e) {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    document.getElementById('imagen').files = e.dataTransfer.files;
    document.getElementById('file-name').textContent = file?.name ?? '';
    document.getElementById('drop-zone').style.borderColor = '#e2e8f0';
}
</script>
@endpush
@endsection