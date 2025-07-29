<?php

namespace HiEvents\Http\Actions\Orders\Payment\MercadoPago;

use Illuminate\Http\Request;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use HiEvents\Events\OrderStatusChangedEvent;
use HiEvents\Models\Order;
use HiEvents\DomainObjects\Status\OrderPaymentStatus;
use HiEvents\DomainObjects\Status\OrderStatus;

use HiEvents\Http\Actions\BaseAction;

class CheckMercadoPagoAction extends BaseAction
{
    public function __invoke(Request $request)
    {
        $shortId = $request->external_reference;
        $order = Order::where('short_id', $shortId)->first();

        if ($request->collection_status == 'approved') {
            $this->authenticate();
            $client = new PaymentClient();

            try {
                $payment = $client->get($request->collection_id);

                if ($payment->status == 'approved') {
                    $order->payment_status = OrderPaymentStatus::PAYMENT_RECEIVED->name;
                    $order->status = OrderStatus::COMPLETED->name;
                    $order->save();

                    OrderStatusChangedEvent::dispatch($order);
                } else {
                    $order->payment_status = OrderPaymentStatus::PAYMENT_FAILED->name;
                    $order->status = OrderStatus::CANCELLED->name;
                    $order->save();
                }
            } catch (MPApiException $e) {
                return $this->jsonResponse([
                    'error' => $e->getApiResponse(),
                ]);
            }
        } else {
            $order->payment_status = OrderPaymentStatus::PAYMENT_FAILED->name;
            $order->status = OrderStatus::CANCELLED->name;
            $order->save();
        }

        return redirect(env('APP_FRONTEND_URL') . '/checkout/' . $order->event_id . '/' . $shortId . '/summary');
    }

    protected function authenticate()
    {
        // Getting the access token from .env file (create your own function)
        $mpAccessToken = env('MP_ACCESS_TOKEN');
        // Set the token the SDK's config
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        // (Optional) Set the runtime enviroment to LOCAL if you want to test on localhost
        // Default value is set to SERVER
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    function createPreferenceRequest($items, $payer): array
    {
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 12,
            "default_installments" => 1
        ];

        $backUrls = array(
            'success' => env('APP_FRONTEND_URL') . '/api/orders/payment/mercadopago/success',
            'failure' => env('APP_FRONTEND_URL') . '/api/orders/payment/mercadopago/failed'
        );

        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING",
            "external_reference" => "1234567890",
            "expires" => false,
            "auto_return" => 'approved',
        ];

        return $request;
    }

    public function createPaymentPreference($order)
    {
        $items = [];
        foreach ($order->order_items as $orderItem) {
            $items[] = [
                "id" => $orderItem->id,
                "title" => $orderItem->item_name,
                "description" => $orderItem->quantity . 'x ' . $orderItem->item_name,
                "currency_id" => "ARS",
                "quantity" => $orderItem->quantity,
                "unit_price" => $orderItem->price
            ];
        }

        $payer = array(
            "name" => $order->attendees->first()->first_name,
            "surname" => $order->attendees->first()->last_name,
            "email" => $order->attendees->first()->email,
        );

        // Create the request object to be sent to the API when the preference is created
        $request = $this->createPreferenceRequest($items, $payer);

        // Instantiate a new Preference Client
        $client = new PreferenceClient();

        try {
            // Send the request that will create the new preference for user's checkout flow
            $preference = $client->create($request);

            // Useful props you could use from this object is 'init_point' (URL to Checkout Pro) or the 'id'
            return $preference;
        } catch (MPApiException $error) {
            // Here you might return whatever your app needs.
            // We are returning null here as an example.
            return null;
        }
    }
}
