<?php

namespace App\Infrastructure\Share\Security\EventListener;

use App\Infrastructure\User\Query\Projections\UserView;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class LoginListener
 * @package App\Infrastructure\Share\Security\EventListener
 */
class LoginListener
{
    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        /** @var UserView $user */
        $user = $event->getAuthenticationToken()->getUser();

        if ($user->isBanned()) {
            throw new AccessDeniedException();
        }
    }
}