<?php

namespace HiEvents\Http\Actions\Orders\Payment\MercadoPago;

use Illuminate\Http\JsonResponse;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use HiEvents\DomainObjects\Status\OrderPaymentStatus;
use HiEvents\Http\Actions\BaseAction;
use HiEvents\Models\Order;

class MercadoPagoAction extends BaseAction
{
    public function __invoke(int $eventId, string $orderShortId): JsonResponse
    {
        $order = new Order();
        $order = $order->query()->where('short_id', $orderShortId)->first();

        $this->authenticate();
        $preference = $this->createPaymentPreference($order);

        return $this->jsonResponse([
            'redirect_url' => env('MP_SANDBOX') ? $preference->sandbox_init_point : $preference->init_point,
        ]);
    }

    protected function authenticate()
    {
        $mpAccessToken = env('MP_ACCESS_TOKEN');
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    function createPreferenceRequest($items, $payer, $eventId, $orderShortId): array
    {
        $paymentMethods = [
            "excluded_payment_types" => array(
                array("id" => "ticket")
            ),
            "default_installments" => 1
        ];

        $backUrls = array(
            'success' => env('MP_CALLBACK'),
            'failure' => env('MP_CALLBACK')
        );

        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => $payer['name'] . ' ' . $payer['surname'],
            "external_reference" => $orderShortId,
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

        $request = $this->createPreferenceRequest($items, $payer, $order->event_id, $order->short_id);

        $client = new PreferenceClient();

        try {
            // Send the request that will create the new preference for user's checkout flow
            $preference = $client->create($request);
            $order->payment()->create([
                'payment_intent_id' => $preference->id,
                'payment_status' => OrderPaymentStatus::AWAITING_PAYMENT->name,
            ]);
            return $preference;
        } catch (MPApiException $error) {
            return null;
        }
    }
}
