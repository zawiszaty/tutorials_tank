<?php

namespace App\Infrastructure\Share\Security\EventListener;

use App\Infrastructure\User\Query\Projections\UserView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class LoginListener.
 */
class LoginListener
{
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): JsonResponse
    {
        /** @var UserView $user */
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user->isEnabled()) {
            return new JsonResponse('Konto jest nie potwierdzone');
        }
    }
}
