<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\Comment\Create\CreateCommentCommand;
use App\Application\Command\Comment\Delete\DeleteCommentCommand;
use App\Application\Query\Comment\GetAllChildrenComment\GetAllChildrenCommentCommand;
use App\Application\Query\Comment\GetAllPostComment\GetAllPostCommentCommand;
use App\Infrastructure\Comment\Repository\CommentRepository;
use App\UI\HTTP\Common\Controller\RestController;
use App\UI\HTTP\Common\Form\CommentTypeForm;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\Dbal\DBALEventStore;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CommentController.
 */
class CommentController extends RestController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createCommentAction(Request $request): Response
    {
        $command = new CreateCommentCommand();
        $command->user = $this->getUser()->getId();

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
     *
     * @return Response
     */
    public function getPostComment(Request $request, string $post): Response
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $command = new GetAllPostCommentCommand($page, $limit, $post);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    /**
     * @param Request $request
     * @param string $parrentComment
     *
     * @return Response
     */
    public function getChildrenComment(Request $request, string $parrentComment): Response
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $command = new GetAllChildrenCommentCommand($page, $limit, $parrentComment);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function deleteCommentAction(Request $request, string $id): Response
    {
        $command = new DeleteCommentCommand($id, $this->getUser()->getId());
        $this->commandBus->handle($command);

        return new JsonResponse('success', 200);
    }
}
