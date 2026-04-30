<?php

namespace App\Http\Controllers\Proveedor;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $productosCount = Producto::deProveedor(Auth::id())->count();
        $pedidosPendientes = Pedido::deProveedor(Auth::id())->where('estado', 'pendiente')->count();
        $enviosActivos = Pedido::deProveedor(Auth::id())->whereIn('estado', ['preparando', 'en_camino'])->count();
        $productosRecientes = Producto::deProveedor(Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('proveedor.dashboard', compact(
            'productosCount',
            'pedidosPendientes',
            'enviosActivos',
            'productosRecientes'
        ));
    }
}