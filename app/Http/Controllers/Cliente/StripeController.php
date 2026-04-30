<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\CarritoItem;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function createCheckout(Request $request)
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

        $subtotal = $items->sum(fn($i) => $i->cantidad * $i->producto->precio);

        $costoEnvio = match($request->transporte) {
            'dron'      => 8000,
            'moto'      => 5000,
            'furgoneta' => 12000,
            default     => 5000,
        };

        $total = $subtotal + $costoEnvio;

        $pedido = DB::transaction(function () use ($request, $items, $subtotal, $costoEnvio, $total) {
            $pedido = Pedido::create([
                'user_id'           => Auth::id(),
                'direccion_entrega' => $request->direccion_entrega,
                'ciudad'            => $request->ciudad,
                'telefono'          => $request->telefono,
                'transporte'        => $request->transporte,
                'estado'            => 'pendiente',
                'subtotal'          => $subtotal,
                'costo_envio'       => $costoEnvio,
                'total'             => $total,
                'notas'             => $request->notas,
                'stripe_payment_status' => 'pending',
            ]);

            foreach ($items as $item) {
                PedidoItem::create([
                    'pedido_id'       => $pedido->id,
                    'producto_id'     => $item->producto_id,
                    'cantidad'        => $item->cantidad,
                    'precio_unitario' => $item->producto->precio,
                    'subtotal'        => $item->cantidad * $item->producto->precio,
                ]);

                $item->producto->decrement('stock', $item->cantidad);
            }

            CarritoItem::where('user_id', Auth::id())->delete();

            return $pedido;
        });

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        $lineItems[] = [
            'price_data' => [
                'currency'     => 'cop',
                'product_data' => [
                    'name' => 'Pedido #' . $pedido->id,
                ],
                'unit_amount'  => $total,
            ],
            'quantity' => 1,
        ];

        $successUrl = route('cliente.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl  = route('cliente.carrito.checkout');

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => $successUrl,
            'cancel_url'           => $cancelUrl,
            'metadata'             => [
                'pedido_id' => $pedido->id,
            ],
        ]);

        $pedido->update(['stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('cliente.pedidos.index')
                ->with('error', 'Sesión de pago no válida.');
        }

        return redirect()->route('cliente.pedidos.index')
            ->with('success', '¡Pago exitoso! Tu pedido ha sido confirmado.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $pedidoId = $session->metadata->pedido_id;

            $pedido = Pedido::find($pedidoId);
            if ($pedido) {
                $pedido->update([
                    'stripe_payment_status' => $session->payment_status,
                    'estado'                => 'confirmado',
                ]);

                Log::info('Pedido #' . $pedido->id . ' confirmado vía Stripe webhook.');
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
