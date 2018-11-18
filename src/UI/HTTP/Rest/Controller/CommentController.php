<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\Comment\Create\CreateCommentCommand;
use App\Application\Query\Comment\GetAllChildrenComment\GetAllChildrenCommentCommand;
use App\Application\Query\Comment\GetAllPostComment\GetAllPostCommentCommand;
use App\Infrastructure\Comment\Repository\CommentRepository;
use App\UI\HTTP\Common\Form\CommentTypeForm;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\Dbal\DBALEventStore;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CommentController
 * @package App\UI\HTTP\Rest\Controller
 */
class CommentController extends Controller
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
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * CommentController constructor.
     * @param CommandBus $queryBus
     * @param CommandBus $commandBus
     * @param EventBus $eventBus
     * @param DBALEventStore $eventStore
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        CommandBus $queryBus,
        CommandBus $commandBus,
        EventBus $eventBus,
        DBALEventStore $eventStore,
        CommentRepository $commentRepository
    )
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->eventStore = $eventStore;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createCommentAction(Request $request): Response
    {
        $command = new CreateCommentCommand();
        $command->setUser($this->getUser()->getId());
//        $command->setUser('127c6fd0-be8d-11e8-a355-529269fb1458');

        $form = $this->createForm(CommentTypeForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new Response('success', Response::HTTP_OK);
        }

        return new JsonResponse($this->getErrorMessages($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param string $post
     * @return Response
     */
    public function getPostComment(Request $request, string $post): Response
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $query = [
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'match' => [
                                'post' => $post,
                            ],
                        ],
                    ],
                    "must_not" => [
                        "exists" => [
                            "field" => "parrentComment"
                        ]
                    ]
                ]
            ]
        ];


        $command = new GetAllPostCommentCommand($page, $limit, $query);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    /**
     * @param Request $request
     * @param string $parrentComment
     * @return Response
     */
    public function getChildrenComment(Request $request, string $parrentComment): Response
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $query = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'parrentComment' => $parrentComment,
                            ],
                        ],
                    ],
                ]
            ]
        ];


        $command = new GetAllChildrenCommentCommand($page, $limit, $query);
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