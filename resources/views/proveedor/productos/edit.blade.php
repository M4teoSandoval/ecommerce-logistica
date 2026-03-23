@extends('layouts.app')
@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')
@section('page-subtitle', '{{ $producto->nombre }}')
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

            <form method="POST" action="{{ route('proveedor.productos.update', $producto) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">Información básica</div>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label">Nombre del producto</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio (COP)</label>
                        <input type="number" name="precio" class="form-control" value="{{ old('precio', $producto->precio) }}" min="0" step="100" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', $producto->stock) }}" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select" required>
                            @foreach(['Electrónica','Ropa','Hogar','Alimentos','Deportes','Libros','Juguetes','Salud','Otros'] as $cat)
                                <option value="{{ $cat }}" {{ old('categoria', $producto->categoria)==$cat ? 'selected':'' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">Dimensiones y peso</div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" name="peso" class="form-control" value="{{ old('peso', $producto->peso) }}" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Alto (cm)</label>
                        <input type="number" name="alto" class="form-control" value="{{ old('alto', $producto->alto) }}" min="0" step="0.1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ancho (cm)</label>
                        <input type="number" name="ancho" class="form-control" value="{{ old('ancho', $producto->ancho) }}" min="0" step="0.1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Largo (cm)</label>
                        <input type="number" name="largo" class="form-control" value="{{ old('largo', $producto->largo) }}" min="0" step="0.1">
                    </div>
                </div>

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">Imagen</div>

                <div class="mb-4">
                    @if($producto->imagen)
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/'.$producto->imagen) }}"
                                 style="width:80px;height:80px;object-fit:cover;border-radius:12px;border:1px solid #f1f5f9;">
                            <div style="font-size:0.8rem;color:#64748b;">Imagen actual. Sube una nueva para reemplazarla.</div>
                        </div>
                    @endif
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                </div>

                <div style="font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:12px;">Estado</div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="activo" id="activo" {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo" style="font-size:0.85rem;color:#475569;">Producto activo (visible para clientes)</label>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('proveedor.productos.index') }}" class="btn" style="border-radius:10px;background:#f1f5f9;color:#475569;font-size:0.85rem;padding:9px 18px;">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-size:0.85rem;padding:9px 18px;">
                        <i class="bi bi-check-lg me-2"></i>Actualizar producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection