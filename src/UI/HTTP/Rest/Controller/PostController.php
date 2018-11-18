<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\Post\Edit\EditPostCommand;
use App\Application\Query\Post\GetAll\GetAllCommand;
use App\Application\Query\Post\GetSingle\GetSingleCommand;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Exception\CreatePostException;
use App\UI\HTTP\Common\Form\AddPostForm;
use App\UI\HTTP\Common\Form\AddPostTypeForm;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\Dbal\DBALEventStore;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController
 * @package App\UI\HTTP\Rest\Controller
 */
class PostController extends Controller
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
    public function addPostAction(Request $request): Response
    {
        $command = new CreatePostCommand();
        $command->setUser($this->getUser()->getId());
        $file = $request->files->get('file');
        $request->request->set('file', $file);
        $form = $this->createForm(AddPostForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($command);
            } catch (CreatePostException $exception) {

                return new JsonResponse('success', Response::HTTP_OK);
            }
        }

        return new JsonResponse($this->getErrorMessages($form), Response::HTTP_BAD_REQUEST);
    }


    /**
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function editPostAction(Request $request, string $id): Response
    {
        $command = new EditPostCommand();
        $command->setUser($this->getUser()->getId());
        $command->setId($id);
        $file = $request->files->get('file');
        $request->request->set('file', $file);
        $form = $this->createForm(AddPostForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($command);
            } catch (CreatePostException $exception) {

                return new JsonResponse('success', Response::HTTP_OK);
            }
        }

        return new JsonResponse($this->getErrorMessages($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return Response
     * @throws \Assert\AssertionFailedException
     */
    public function getSingle(Request $request, string $id): Response
    {
        $command = new GetSingleCommand(AggregateRootId::fromString($id));
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getAllAction(Request $request): Response
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        if ($request->get('query')) {
            $query = [
                'query' => [
                    'wildcard' => [
                        'title' => '*' . $request->get('query') . '*',
                    ],
                ],
            ];
        } else {
            $query = [];
        }

        $command = new GetAllCommand($page, $limit, $query);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}