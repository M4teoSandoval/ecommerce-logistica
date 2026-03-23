<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeguimientoController extends Controller
{
    public function index(Pedido $pedido)
    {
        abort_if($pedido->user_id !== Auth::id(), 403);

        $pedido->load(['seguimientos', 'items.producto', 'ultimoSeguimiento']);

        return view('cliente.seguimiento.index', compact('pedido'));
    }

    public function estado(Pedido $pedido)
    {
        abort_if($pedido->user_id !== Auth::id(), 403);

        $ultimo = $pedido->ultimoSeguimiento;

        return response()->json([
            'estado'      => $pedido->estado,
            'seguimientos'=> $pedido->seguimientos->map(fn($s) => [
                'estado'      => $s->estado,
                'descripcion' => $s->descripcion,
                'icono'       => $s->icono,
                'color'       => $s->color,
                'ubicacion'   => $s->ubicacion_descripcion,
                'hora'        => $s->created_at->format('H:i'),
                'fecha'       => $s->created_at->format('d/m/Y'),
            ]),
            'ultimo' => $ultimo ? [
                'estado'      => $ultimo->estado,
                'descripcion' => $ultimo->descripcion,
                'latitud'     => $ultimo->latitud,
                'longitud'    => $ultimo->longitud,
                'ubicacion'   => $ultimo->ubicacion_descripcion,
            ] : null,
        ]);
    }
}