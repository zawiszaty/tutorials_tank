<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\Post\Create\CreatePostCommand;
use App\Application\Command\Post\Delete\DeletePostCommand;
use App\Application\Command\Post\Edit\EditPostCommand;
use App\Application\Query\Post\GetAll\GetAllCommand;
use App\Application\Query\Post\GetOneBySlug\GetOneBySlugCommand;
use App\Application\Query\Post\GetSingle\GetSingleCommand;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\UI\HTTP\Common\Controller\RestController;
use App\UI\HTTP\Common\Form\EditPostForm;
use App\UI\HTTP\Common\Form\PostForm;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController.
 */
class PostController extends RestController
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     *
     * @SWG\Parameter(
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="content", type="string"),
     *         @SWG\Property(property="title", type="string"),
     *         @SWG\Property(property="file", type="file"),
     *         @SWG\Property(property="type", type="string"),
     *         @SWG\Property(property="category", type="string"),
     *         @SWG\Property(property="user", type="string"),
     *         @SWG\Property(property="shortDescription", type="string"),
     *     )
     * )
     *
     * @SWG\Tag(name="Post")
     * @NelmioSecurity(name="BearerUser")
     */
    public function addPostAction(Request $request): Response
    {
        $command = new CreatePostCommand();
        $command->user = $this->getUser()->getId();
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
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     *
     * @SWG\Parameter(
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="content", type="string"),
     *         @SWG\Property(property="title", type="string"),
     *         @SWG\Property(property="file", type="file"),
     *         @SWG\Property(property="type", type="string"),
     *         @SWG\Property(property="category", type="string"),
     *         @SWG\Property(property="user", type="string"),
     *         @SWG\Property(property="shortDescription", type="string"),
     *     )
     * )
     *
     * @SWG\Tag(name="Post")
     * @NelmioSecurity(name="BearerUser")
     */
    public function editPostAction(Request $request, string $id): Response
    {
        $command = new EditPostCommand();
        $command->user = $this->getUser()->getId();
        $command->id = $id;
        $file = $request->files->get('file');
        $request->request->set('file', $file);
        $form = $this->createForm(EditPostForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new JsonResponse('success', Response::HTTP_OK);
        }

        return new JsonResponse($this->getErrorMessages($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     *
     *
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     type="string",
     *     in="path",
     * )
     *
     * @SWG\Tag(name="Post")
     * @NelmioSecurity(name="BearerUser")
     */
    public function getSingle(Request $request, string $id): Response
    {
        $command = new GetSingleCommand(AggregateRootId::fromString($id));
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     *
     * @SWG\Parameter(
     *     name="slug",
     *     type="string",
     *     in="path",
     * )
     *
     * @SWG\Tag(name="Post")
     */
    public function getSingleBySlug(Request $request, string $slug): Response
    {
        $command = new GetOneBySlugCommand();
        $command->slug = $slug;
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     *
     * @SWG\Parameter(
     *     name="page",
     *     type="string",
     *     in="query",
     * )
     *
     * @SWG\Parameter(
     *     name="limit",
     *     type="string",
     *     in="query",
     * )
     *
     * @SWG\Parameter(
     *     name="query",
     *     type="string",
     *     in="query",
     * )
     *
     * @SWG\Tag(name="Post")
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
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     *
     *
     * @SWG\Tag(name="Post")
     * @NelmioSecurity(name="BearerUser")
     */
    public function deletePostAction(Request $request, string $id): Response
    {
        $command = new DeletePostCommand($id, $this->getUser()->getId());
        $this->commandBus->handle($command);

        return new JsonResponse('success', 200);
    }
}
