<?php

namespace App\Http\Controllers\Repartidor;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntregaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::asignadoA(Auth::id())
            ->with(['cliente', 'ultimoSeguimiento'])
            ->whereNotIn('estado', ['cancelado']);

        $estado = $request->query('estado');
        $validEstados = ['pendiente', 'confirmado', 'preparando', 'en_camino', 'cerca', 'entregado'];

        if ($estado && in_array($estado, $validEstados)) {
            $query->where('estado', $estado);
        }

        $entregas = $query->orderBy('updated_at', 'desc')->paginate(15);

        $counters = [
            'todos' => Pedido::asignadoA(Auth::id())->whereNotIn('estado', ['cancelado'])->count(),
            'preparando' => Pedido::asignadoA(Auth::id())->where('estado', 'preparando')->count(),
            'en_camino' => Pedido::asignadoA(Auth::id())->where('estado', 'en_camino')->count(),
            'cerca' => Pedido::asignadoA(Auth::id())->where('estado', 'cerca')->count(),
            'entregado' => Pedido::asignadoA(Auth::id())->where('estado', 'entregado')->count(),
        ];

        return view('repartidor.entregas.index', compact('entregas', 'counters', 'estado'));
    }

    public function show(Pedido $pedido)
    {
        abort_if($pedido->repartidor_id !== Auth::id(), 403);

        $pedido->load(['cliente', 'seguimientos', 'items.producto']);

        $estadosDisponibles = $this->estadosSiguientes($pedido->estado);

        return view('repartidor.entregas.show', compact('pedido', 'estadosDisponibles'));
    }

    public function actualizarEstado(Request $request, Pedido $pedido)
    {
        abort_if($pedido->repartidor_id !== Auth::id(), 403);

        $estadosPermitidos = ['en_camino', 'cerca', 'entregado'];
        $nuevoEstado = $request->estado;

        abort_if(!in_array($nuevoEstado, $estadosPermitidos), 403, 'Estado no permitido para repartidores.');

        $orden = ['en_camino', 'cerca', 'entregado'];
        $indiceActual = array_search($pedido->estado, $orden);
        $indiceNuevo = array_search($nuevoEstado, $orden);

        abort_if($indiceNuevo <= $indiceActual, 403, 'No puedes retroceder el estado de una entrega.');

        $descripcion = $request->descripcion ?? $this->descripcionDefault($nuevoEstado);

        Seguimiento::create([
            'pedido_id'            => $pedido->id,
            'estado'               => $nuevoEstado,
            'descripcion'          => $descripcion,
            'latitud'              => $request->latitud,
            'longitud'             => $request->longitud,
            'ubicacion_descripcion'=> $request->ubicacion_descripcion,
        ]);

        $pedido->update(['estado' => $nuevoEstado]);

        return back()->with('success', 'Entrega actualizada a: ' . ucfirst(str_replace('_', ' ', $nuevoEstado)));
    }

    private function estadosSiguientes(string $estadoActual): array
    {
        $orden = ['en_camino', 'cerca', 'entregado'];
        $indice = array_search($estadoActual, $orden);

        if ($indice === false || $indice >= count($orden) - 1) {
            return [];
        }

        return array_slice($orden, $indice + 1);
    }

    private function descripcionDefault(string $estado): string
    {
        return match ($estado) {
            'en_camino' => 'En camino al destino.',
            'cerca'     => 'Cerca del punto de entrega.',
            'entregado' => 'Entrega completada exitosamente.',
            default     => 'Estado actualizado por el repartidor.',
        };
    }
}
