<?php

namespace App\UI\Rest\Controller;

use App\Application\Command\Category\ChangeName\ChangeNameCommand;
use App\Application\Command\Category\Create\CreateCategoryCommand;
use Assert\Assertion;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\Dbal\DBALEventStore;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
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

    public function __construct(CommandBus $queryBus, CommandBus $commandBus, EventBus $eventBus, DBALEventStore $eventStore)
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->eventStore = $eventStore;
    }

    /**
     * @Route("/test")
     * @param Request $request
     * @return Response
     * @throws \Assert\AssertionFailedException
     */
    public function createCategoryAction(Request $request): Response
    {
        $name = $request->request->get('name');

        Assertion::notNull($name);

        $command = new CreateCategoryCommand($name);
        $this->commandBus->handle($command);

        return new JsonResponse('działa', 200);
    }

    /**
     * @Route("/test2")
     *
     * @param Request $request
     * @return Response
     * @throws \Assert\AssertionFailedException
     */
    public function changeNameAction(Request $request): Response
    {
        $id = $request->request->get('id');
        $name = $request->request->get('name');

        Assertion::notNull($id);
        Assertion::notNull($name);

        $command = new ChangeNameCommand($id,$name);
        $this->commandBus->handle($command);

        return new JsonResponse('działą', 200);
    }
}