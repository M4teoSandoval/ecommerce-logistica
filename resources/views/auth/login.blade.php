@extends('layouts.app')
@section('title', 'Iniciar Sesión')
@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo"><i class="bi bi-box-seam"></i></div>
        <h5 class="text-center fw-700 mb-1" style="font-weight:700; color:#0f172a;">Bienvenido de nuevo</h5>
        <p class="text-center text-muted mb-4" style="font-size:0.85rem;">Ingresa a tu cuenta para continuar</p>

        @if($errors->any())
            <div class="alert alert-danger mb-3"><i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="tu@correo.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-auth">Iniciar sesión</button>
        </form>

        <p class="text-center mt-4 mb-0" style="font-size:0.82rem; color:#64748b;">
            ¿No tienes cuenta? <a href="/register" style="color:#6366f1; font-weight:600;">Regístrate aquí</a>
        </p>
    </div>
</div>
@endsection