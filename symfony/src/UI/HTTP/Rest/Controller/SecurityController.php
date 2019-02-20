<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Infrastructure\User\Query\Projections\UserView;
use App\UI\HTTP\Common\Controller\RestController;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController.
 */
class SecurityController extends RestController
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     *
     *
     * @SWG\Tag(name="Security")
     * @NelmioSecurity(name="BearerUser")
     * @NelmioSecurity(name="BearerAdmin")
     */
    public function securityAction(Request $request): Response
    {
        /** @var UserView $user */
        $user = $this->getUser();

        if ($user->isBanned()) {
            return new JsonResponse([
                '#' => ['Konto zbanowane'],
            ], 400);
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
