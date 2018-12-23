<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Domain\User\Exception\UserIsBannedException;
use App\Infrastructure\User\Query\Projections\UserView;
use App\UI\HTTP\Common\Controller\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController.
 */
class SecurityController extends RestController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function securityAction(Request $request): Response
    {
        /** @var UserView $user */
        $user = $this->getUser();

        if ($user->isBanned()) {
            throw new UserIsBannedException();
        }

        return new JsonResponse([
            'id'     => $user->getId(),
            'name'   => $user->getUsername(),
            'email'  => $user->getEmail(),
            'roles'  => $user->getRoles(),
            'avatar' => $user->getAvatar(),
        ]);
    }
}
