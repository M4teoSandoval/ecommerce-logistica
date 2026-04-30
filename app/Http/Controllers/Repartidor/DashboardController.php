<?php

namespace App\Http\Controllers\Repartidor;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->startOfDay();

        $entregasHoy = Pedido::asignadoA(Auth::id())
            ->whereDate('updated_at', '>=', $hoy)
            ->count();

        $entregasCompletadasHoy = Pedido::asignadoA(Auth::id())
            ->where('estado', 'entregado')
            ->whereDate('updated_at', '>=', $hoy)
            ->count();

        $enCamino = Pedido::asignadoA(Auth::id())
            ->where('estado', 'en_camino')
            ->count();

        $pendientes = Pedido::asignadoA(Auth::id())
            ->where('estado', 'preparando')
            ->count();

        $entregasRecientes = Pedido::asignadoA(Auth::id())
            ->with(['cliente', 'ultimoSeguimiento'])
            ->whereNotIn('estado', ['entregado', 'cancelado'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('repartidor.dashboard', compact(
            'entregasHoy',
            'entregasCompletadasHoy',
            'enCamino',
            'pendientes',
            'entregasRecientes'
        ));
    }
}
