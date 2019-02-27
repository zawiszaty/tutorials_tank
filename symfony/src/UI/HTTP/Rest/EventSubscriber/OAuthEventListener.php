<?php

namespace App\UI\HTTP\Rest\EventSubscriber;

use App\Infrastructure\User\Repository\UserRepository;
use FOS\OAuthServerBundle\Event\OAuthEvent;

class OAuthEventListener
{
    /**
     * @var \App\Infrastructure\User\Query\Repository\UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function onPreAuthorizationProcess(OAuthEvent $event)
    {
        if ($user = $this->getUser($event)) {
//            $event->setAuthorizedClient(
//                $user->isAuthorizedClient($event->getClient())
//            );
            var_dump('321');
            die();
        }
        var_dump('321');
        die();
    }

    public function onPostAuthorizationProcess(OAuthEvent $event)
    {
        if ($event->isAuthorizedClient()) {
            if (null !== $client = $event->getClient()) {
                var_dump('123');
                die();
            }
        }
        var_dump('321');
        die();
    }

    protected function getUser(OAuthEvent $event)
    {
        return $this->userRepository->findOneBy(['username' => $event->getUser()->getUsername()]);
    }
}
