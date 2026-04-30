@extends('layouts.app')
@section('title', 'Crear cuenta')
@section('content')
<div class="auth-wrapper">
    <div class="auth-card" style="max-width:460px;">
        <div class="auth-logo"><i class="bi bi-person-plus"></i></div>
        <h5 class="text-center fw-700 mb-1" style="font-weight:700; color:#0f172a;">Crear cuenta</h5>
        <p class="text-center text-muted mb-4" style="font-size:0.85rem;">Únete a EcoLogística hoy</p>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)
                        <li style="font-size:0.82rem;">{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Juan Pérez" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="tu@correo.com" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="300 000 0000">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo de cuenta</label>
                    <select name="role" class="form-select" required>
                        <option value="">Selecciona...</option>
                        <option value="cliente"   {{ old('role')=='cliente'   ? 'selected':'' }}>Cliente</option>
                        <option value="proveedor" {{ old('role')=='proveedor' ? 'selected':'' }}>Proveedor</option>
                        <option value="repartidor" {{ old('role')=='repartidor' ? 'selected':'' }}>Repartidor</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la contraseña" required>
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn-auth">Crear cuenta</button>
                </div>
            </div>
        </form>

        <p class="text-center mt-4 mb-0" style="font-size:0.82rem; color:#64748b;">
            ¿Ya tienes cuenta? <a href="/login" style="color:#6366f1; font-weight:600;">Inicia sesión</a>
        </p>
    </div>
</div>
@endsection