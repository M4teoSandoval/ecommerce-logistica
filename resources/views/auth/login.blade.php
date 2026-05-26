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
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="tu@correo.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-auth">Iniciar sesión</button>
        </form>

        <div style="margin-top:20px;padding-top:16px;border-top:1px solid #e2e8f0;">
            <div style="font-size:0.75rem;font-weight:600;color:#94a3b8;text-align:center;margin-bottom:10px;text-transform:uppercase;letter-spacing:0.05em;">
                Acceso rápido
            </div>
            <div style="display:flex;flex-direction:column;gap:6px;">
                <button type="button" class="btn-login-quick" data-email="admin@ecommerce.com" data-password="admin123">
                    <i class="bi bi-shield-lock me-2"></i>Admin
                </button>
                <button type="button" class="btn-login-quick" data-email="proveedor@ecommerce.com" data-password="proveedor123">
                    <i class="bi bi-box-seam me-2"></i>Proveedor
                </button>
                <button type="button" class="btn-login-quick" data-email="repartidor@test.com" data-password="12345678">
                    <i class="bi bi-bicycle me-2"></i>Repartidor
                </button>
                <button type="button" class="btn-login-quick" data-email="cliente@ecommerce.com" data-password="cliente123">
                    <i class="bi bi-person me-2"></i>Cliente
                </button>
            </div>
        </div>

        <p class="text-center mt-4 mb-0" style="font-size:0.82rem; color:#64748b;">
            ¿No tienes cuenta? <a href="/register" style="color:#6366f1; font-weight:600;">Regístrate aquí</a>
        </p>

        <script>
            document.querySelectorAll('.btn-login-quick').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('email').value = this.dataset.email;
                    document.getElementById('password').value = this.dataset.password;
                });
            });
        </script>
    </div>
</div>
@endsection