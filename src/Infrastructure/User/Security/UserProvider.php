<?php

namespace App\Infrastructure\User\Security;

use App\Infrastructure\User\Query\Projections\UserView;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->fetchUser($username);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserView) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $username = $user->getUsername();

        return $this->fetchUser($username);
    }

    public function supportsClass($class)
    {
        return UserView::class === $class;
    }
    private function fetchUser($username)
    {
//        // make a call to your webservice here
//        $userData = ...
//        // pretend it returns an array on success, false if there is no user
//
//        if ($userData) {
//            $password = '...';
//
//            // ...
//
//            return new WebserviceUser($username, $password, $salt, $roles);
//        }
//
//        throw new UsernameNotFoundException(
//            sprintf('Username "%s" does not exist.', $username)
//        );
    }
}