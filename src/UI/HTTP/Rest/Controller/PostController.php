<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\Post\Delete\DeletePostCommand;
use App\Application\Command\Post\Edit\EditPostCommand;
use App\Application\Query\Post\GetAll\GetAllCommand;
use App\Application\Query\Post\GetSingle\GetSingleCommand;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\UI\HTTP\Common\Controller\RestController;
use App\UI\HTTP\Common\Form\PostForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController.
 */
class PostController extends RestController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addPostAction(Request $request): Response
    {
        $command = new CreatePostCommand();
        $command->setUser($this->getUser()->getId());
        $file = $request->files->get('file');
        $request->request->set('file', $file);
        $form = $this->createForm(PostForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new JsonResponse('success', Response::HTTP_OK);
        }

        return new JsonResponse($this->getErrorMessages($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param string $id
     *
     * @return Response
     */
    public function editPostAction(Request $request, string $id): Response
    {
        $command = new EditPostCommand();
        $command->setUser($this->getUser()->getId());
        $command->setId($id);
        $file = $request->files->get('file');
        $request->request->set('file', $file);
        $form = $this->createForm(PostForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new JsonResponse('success', Response::HTTP_OK);
        }

        return new JsonResponse($this->getErrorMessages($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param string $id
     *
     * @return Response
     *
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
     *
     * @return Response
     */
    public function getAllAction(Request $request): Response
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $query = $request->get('query') ?? null;

        $command = new GetAllCommand($page, $limit, $query);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function deletePostAction(Request $request, string $id): Response
    {
        $command = new DeletePostCommand($id, $this->getUser()->getId());
        $this->commandBus->handle($command);

        return new JsonResponse('success', 200);
    }
}
