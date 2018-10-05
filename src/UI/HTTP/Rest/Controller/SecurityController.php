<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Domain\User\Assert\UserIsBanned;
use App\Domain\User\Exception\UserIsBannedException;
use App\Infrastructure\User\Query\Projections\UserView;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\Dbal\DBALEventStore;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController
 * @package App\UI\HTTP\Rest\Controller
 */
class SecurityController extends Controller
{
    /**
     * @var CommandBus
     */
    private $queryBus;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @var DBALEventStore
     */
    private $eventStore;

    public function __construct(
        CommandBus $queryBus,
        CommandBus $commandBus,
        EventBus $eventBus,
        DBALEventStore $eventStore
    )
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->eventStore = $eventStore;
    }

    /**
     * @param Request $request
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
            "name" => $user->getUsername(),
            "email" => $user->getEmail(),
            "roles" => $user->getRoles(),
            "avatar" => $user->getAvatar()
        ]);
    }
}