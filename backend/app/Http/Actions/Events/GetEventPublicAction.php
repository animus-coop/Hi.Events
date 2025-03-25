<?php

namespace HiEvents\Http\Actions\Events;

use HiEvents\DomainObjects\Status\EventStatus;
use HiEvents\Http\Actions\BaseAction;
use HiEvents\Resources\Event\EventResourcePublic;
use HiEvents\Services\Handlers\Event\DTO\GetPublicEventDTO;
use HiEvents\Services\Handlers\Event\GetPublicEventHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;
use VirtualQueue\TokenVerifier\Laravel\Facades\TokenVerifier;

class GetEventPublicAction extends BaseAction
{
    public function __construct(
        private readonly GetPublicEventHandler $handler,
        private readonly LoggerInterface       $logger,
    )
    {
    }

    public function __invoke(int $eventId, Request $request): Response|JsonResponse
    {
        if ($request->token) {
            $result = TokenVerifier::verifyToken($request->token);
            $this->logger->debug('Validating token');

            // Solo si queremos true o false
            // $isValid = TokenVerifier::isTokenValid($request->token);
            // $this->logger->debug(__('Token is valid', [
            //     'token_validity' => $isValid
            // ]));

            if (isset($result['success']) && $result['success'] == false) {
                $this->logger->debug(__('Token is invalid: :message', [
                    'message' => json_encode($result['message'])
                ]));

                return $this->notFoundResponse();
            }
        }

        $event = $this->handler->handle(GetPublicEventDTO::fromArray([
            'eventId' => $eventId,
            'ipAddress' => $this->getClientIp($request),
            'promoCode' => strtolower($request->string('promo_code')),
            'isAuthenticated' => $this->isUserAuthenticated(),
        ]));

        if ($event->getStatus() !== EventStatus::LIVE->name && !$this->isUserAuthenticated()) {
            $this->logger->debug(__('Event with ID :eventId is not live and user is not authenticated', [
                'eventId' => $eventId
            ]));
            return $this->notFoundResponse();
        }

        return $this->resourceResponse(EventResourcePublic::class, $event);
    }
}
