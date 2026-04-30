<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Proveedor\DashboardController as ProveedorDashboard;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboard;


Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'administrador' => redirect()->route('admin.dashboard'),
            'proveedor'     => redirect()->route('proveedor.dashboard'),
            'repartidor'    => redirect()->route('repartidor.dashboard'),
            default         => redirect()->route('cliente.dashboard'),
        };
    }
    return view('home');
})->name('home');

// Auth
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// Panel administrador
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('productos', \App\Http\Controllers\Proveedor\ProductoController::class);

    // Drones
    Route::resource('drones', \App\Http\Controllers\Admin\DroneController::class);

    // Mantenimientos
    Route::get('/mantenimientos', [\App\Http\Controllers\Admin\MantenimientoController::class, 'index'])->name('mantenimientos.index');
    Route::get('/mantenimientos/crear', [\App\Http\Controllers\Admin\MantenimientoController::class, 'create'])->name('mantenimientos.create');
    Route::post('/mantenimientos', [\App\Http\Controllers\Admin\MantenimientoController::class, 'store'])->name('mantenimientos.store');
    Route::patch('/mantenimientos/{mantenimiento}', [\App\Http\Controllers\Admin\MantenimientoController::class, 'update'])->name('mantenimientos.update');

    // Simulación
    Route::get('/simulacion', [\App\Http\Controllers\Admin\SimulacionController::class, 'index'])->name('simulacion.index');
    Route::post('/simulacion', [\App\Http\Controllers\Admin\SimulacionController::class, 'simular'])->name('simulacion.simular');

    Route::get('/seguimiento', [\App\Http\Controllers\Admin\SeguimientoController::class, 'index'])->name('seguimiento.index');
    Route::get('/seguimiento/{pedido}', [\App\Http\Controllers\Admin\SeguimientoController::class, 'pedido'])->name('seguimiento.pedido');
    Route::post('/seguimiento/{pedido}', [\App\Http\Controllers\Admin\SeguimientoController::class, 'actualizar'])->name('seguimiento.actualizar');
    Route::post('/seguimiento/{pedido}/asignar', [\App\Http\Controllers\Admin\SeguimientoController::class, 'asignarRepartidor'])->name('seguimiento.asignar');
});

// Panel proveedor
Route::middleware(['auth', 'role:proveedor'])->prefix('proveedor')->name('proveedor.')->group(function () {
    Route::get('/dashboard', [ProveedorDashboard::class, 'index'])->name('dashboard');
    Route::resource('productos', \App\Http\Controllers\Proveedor\ProductoController::class);

    // Pedidos
    Route::get('/pedidos', [\App\Http\Controllers\Proveedor\PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}', [\App\Http\Controllers\Proveedor\PedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/pedidos/{pedido}/actualizar', [\App\Http\Controllers\Proveedor\PedidoController::class, 'actualizarEstado'])->name('pedidos.actualizar');

    // Envíos
    Route::get('/envios', [\App\Http\Controllers\Proveedor\EnvioController::class, 'index'])->name('envios.index');
});

// Panel cliente
Route::middleware(['auth', 'role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [ClienteDashboard::class, 'index'])->name('dashboard');
    Route::get('/productos', [\App\Http\Controllers\Cliente\ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/{producto}', [\App\Http\Controllers\Cliente\ProductoController::class, 'show'])->name('productos.show');

    // Carrito
    Route::get('/carrito', [\App\Http\Controllers\Cliente\CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/{producto}', [\App\Http\Controllers\Cliente\CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::patch('/carrito/item/{item}', [\App\Http\Controllers\Cliente\CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/item/{item}', [\App\Http\Controllers\Cliente\CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::get('/checkout', [\App\Http\Controllers\Cliente\CarritoController::class, 'checkout'])->name('carrito.checkout');
    Route::post('/checkout', [\App\Http\Controllers\Cliente\CarritoController::class, 'procesarPedido'])->name('carrito.procesar');
    Route::get('/pedidos', [\App\Http\Controllers\Cliente\CarritoController::class, 'pedidos'])->name('pedidos.index');

    Route::get('/seguimiento/{pedido}', [\App\Http\Controllers\Cliente\SeguimientoController::class, 'index'])->name('seguimiento.index');
    Route::get('/seguimiento/{pedido}/estado', [\App\Http\Controllers\Cliente\SeguimientoController::class, 'estado'])->name('seguimiento.estado');
});

// Panel repartidor
Route::middleware(['auth', 'role:repartidor'])->prefix('repartidor')->name('repartidor.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Repartidor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/entregas', [\App\Http\Controllers\Repartidor\EntregaController::class, 'index'])->name('entregas.index');
    Route::get('/entregas/{pedido}', [\App\Http\Controllers\Repartidor\EntregaController::class, 'show'])->name('entregas.show');
    Route::post('/entregas/{pedido}/actualizar', [\App\Http\Controllers\Repartidor\EntregaController::class, 'actualizarEstado'])->name('entregas.actualizar');
});
