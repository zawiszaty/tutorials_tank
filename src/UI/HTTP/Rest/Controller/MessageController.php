<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Query\Message\GetAll\GetAllCommand;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\Dbal\DBALEventStore;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MessageController
 * @package App\UI\HTTP\Rest\Controller
 */
class MessageController extends Controller
{
    /**
     * @var CommandBus
     */
    private $queryBus;

    /**
     * @var CommandBus
     *
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
    public function getAllUserMessage(Request $request): Response
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $recipient = $request->get('recipient');
        if ($request->get('query')) {
            $query = [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'recipient' => $recipient,
                                ],
                            ],
                            [
                                'match' => [
                                    'recipient' => $this->getUser()->getId(),
                                ],
                            ],
                        ],
                    ]
                ],
            ];
        } else {
            $query = [];
        }

        $command = new GetAllCommand($page, $limit, $query);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }
}