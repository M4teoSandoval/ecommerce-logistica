<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\CarritoItem;
use App\Models\Drone;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\Seguimiento;
use App\Services\FacturacionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarritoController extends Controller
{
    public function index()
    {
        $items = CarritoItem::where('user_id', Auth::id())
            ->with('producto')
            ->get();

        $subtotal = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);

        return view('cliente.carrito.index', compact('items', 'subtotal'));
    }

    public function agregar(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $producto->stock,
        ]);

        $item = CarritoItem::where('user_id', Auth::id())
            ->where('producto_id', $producto->id)
            ->first();

        if ($item) {
            $nuevaCantidad = min($item->cantidad + $request->cantidad, $producto->stock);
            $item->update(['cantidad' => $nuevaCantidad]);
        } else {
            CarritoItem::create([
                'user_id'     => Auth::id(),
                'producto_id' => $producto->id,
                'cantidad'    => $request->cantidad,
            ]);
        }

        return redirect()->route('cliente.carrito.index')
            ->with('success', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, CarritoItem $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);

        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $item->producto->stock,
        ]);

        $item->update(['cantidad' => $request->cantidad]);

        return redirect()->route('cliente.carrito.index');
    }

    public function eliminar(CarritoItem $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);
        $item->delete();
        return redirect()->route('cliente.carrito.index')
            ->with('success', 'Producto eliminado del carrito.');
    }

    public function checkout()
    {
        $items = CarritoItem::where('user_id', Auth::id())
            ->with('producto')
            ->get();

        abort_if($items->isEmpty(), 302, redirect()->route('cliente.carrito.index'));

        $subtotal = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);
        $pesoTotal = $items->sum(fn($i) => $i->cantidad * $i->producto->peso);
        $maxPesoDron = Drone::where('estado', 'disponible')->max('peso_maximo');

        return view('cliente.carrito.checkout', compact('items', 'subtotal', 'pesoTotal', 'maxPesoDron'));
    }

    public function procesarPedido(Request $request)
    {
        $request->validate([
            'direccion_entrega' => 'required|string|max:255',
            'ciudad'            => 'required|string|max:100',
            'telefono'          => 'required|string|max:20',
            'transporte'        => 'required|in:dron,moto,furgoneta',
            'notas'             => 'nullable|string',
        ]);

        $items = CarritoItem::where('user_id', Auth::id())
            ->with('producto')
            ->get();

        abort_if($items->isEmpty(), 403);

        if ($request->transporte === 'dron') {
            $pesoTotal = $items->sum(fn($i) => $i->cantidad * $i->producto->peso);
            $maxPesoDron = Drone::where('estado', 'disponible')->max('peso_maximo');

            if ($maxPesoDron && $pesoTotal > $maxPesoDron) {
                return back()->withErrors([
                    'transporte' => "El peso total del pedido ($pesoTotal kg) supera la capacidad máxima del dron ($maxPesoDron kg). Por favor elige moto o furgoneta.",
                ])->withInput();
            }
        }

        $subtotal = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);

        $costoEnvio = match($request->transporte) {
            'dron'      => 8000,
            'moto'      => 5000,
            'furgoneta' => 12000,
            default     => 5000,
        };

        $pedido = DB::transaction(function () use ($request, $items, $subtotal, $costoEnvio) {
            $pedido = Pedido::create([
                'user_id'           => Auth::id(),
                'direccion_entrega' => $request->direccion_entrega,
                'ciudad'            => $request->ciudad,
                'telefono'          => $request->telefono,
                'transporte'        => $request->transporte,
                'estado'            => 'pendiente',
                'subtotal'          => $subtotal,
                'costo_envio'       => $costoEnvio,
                'total'             => $subtotal + $costoEnvio,
                'notas'             => $request->notas,
            ]);

            foreach ($items as $item) {
                PedidoItem::create([
                    'pedido_id'      => $pedido->id,
                    'producto_id'    => $item->producto_id,
                    'cantidad'       => $item->cantidad,
                    'precio_unitario'=> $item->producto->precio,
                    'subtotal'       => $item->cantidad * $item->producto->precio,
                ]);

                $item->producto->decrement('stock', $item->cantidad);
            }

            CarritoItem::where('user_id', Auth::id())->delete();

            return $pedido;
        });

        try {
            app(FacturacionService::class)->generar($pedido);
        } catch (\Exception $e) {
            Log::error('Error al generar factura: ' . $e->getMessage());
        }

        return redirect()->route('cliente.pedidos.index')
            ->with('success', '¡Pedido realizado exitosamente!');
    }

    public function pedidos()
    {
        $pedidos = Pedido::where('user_id', Auth::id())
            ->with('items.producto', 'factura')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.pedidos.index', compact('pedidos'));
    }

    public function cancelar(Pedido $pedido)
    {
        abort_if($pedido->user_id !== Auth::id(), 403);

        $estadosPermitidos = ['pendiente', 'confirmado'];
        if (!in_array($pedido->estado, $estadosPermitidos)) {
            return back()->with('error', 'Este pedido ya no puede cancelarse porque está en tránsito o ya fue entregado.');
        }

        $tiempoLimiteMinutos = 30;
        if ($pedido->stripe_payment_status === 'paid') {
            $minutosDesdeCreacion = Carbon::parse($pedido->created_at)->diffInMinutes(now());
            if ($minutosDesdeCreacion > $tiempoLimiteMinutos) {
                return back()->with('error', "Han pasado más de {$tiempoLimiteMinutos} minutos desde el pago. Ya no es posible cancelar.");
            }
        }

        DB::transaction(function () use ($pedido) {
            foreach ($pedido->items as $item) {
                $item->producto->increment('stock', $item->cantidad);
            }

            $pedido->update([
                'estado'      => 'cancelado',
                'canceled_at' => now(),
            ]);

            if ($pedido->stripe_payment_status === 'paid') {
                $pedido->update(['stripe_payment_status' => 'refunded']);
            }

            Seguimiento::create([
                'pedido_id'   => $pedido->id,
                'estado'      => 'cancelado',
                'descripcion' => $pedido->stripe_payment_status === 'refunded'
                    ? 'Pedido cancelado por el cliente. Pago reembolsado (simulado).'
                    : 'Pedido cancelado por el cliente.',
            ]);
        });

        return redirect()->route('cliente.pedidos.index')
            ->with('success', 'Pedido #' . $pedido->id . ' cancelado exitosamente.');
    }
}