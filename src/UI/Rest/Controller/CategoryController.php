<?php

namespace App\UI\Rest\Controller;

use App\Application\Command\Category\ChangeName\ChangeNameCommand;
use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Category\Delete\DeleteCategoryCommand;
use App\Application\Query\Category\GetAll\GetAllCommand;
use App\Application\Query\Category\GetSingle\GetSingleCommand;
use App\Domain\Category\Exception\CategoryCreateException;
use App\Domain\Common\ValueObject\AggregatRootId;
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
     * @Route("/category", name="add_category", methods="POST")
     * @param Request $request
     * @return Response
     * @throws \Assert\AssertionFailedException
     */
    public function createCategoryAction(Request $request): Response
    {
        $name = $request->request->get('name');

        Assertion::notNull($name);

        try {
            $command = new CreateCategoryCommand($name);
            $this->commandBus->handle($command);
        } catch (CategoryCreateException $exception)
        {
            return new JsonResponse(['id' => $exception->getMessage()], 200);
        }
    }

    /**
     * @Route("/category", name="edit_category", methods="PUT")
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

        $command = new ChangeNameCommand($id, $name);
        $this->commandBus->handle($command);

        return new JsonResponse('success', 200);
    }

    /**
     * @Route("/category/{id}", name="delete_category", methods="DELETE")
     *
     * @param Request $request
     * @param string $id
     * @return Response
     * @throws \Assert\AssertionFailedException
     */
    public function deleteCategoryAction(Request $request, string $id): Response
    {
        $command = new DeleteCategoryCommand($id);
        $this->commandBus->handle($command);

        return new JsonResponse('success', 200);
    }

    /**
     * @Route("/category", name="get_all_category")
     *
     * @return Response
     */
    public function getAllCategoryAction(): Response
    {
        $command = new GetAllCommand();
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    /**
     * @Route("/category/{id}", name="get_single_category")
     *
     * @param Request $request
     * @param string $id
     * @return Response
     * @throws \Assert\AssertionFailedException
     */
    public function getSingleAction(Request $request, string $id): Response
    {
        $aggregatRootId = AggregatRootId::fromString($id);

        $command = new GetSingleCommand($aggregatRootId);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }
}