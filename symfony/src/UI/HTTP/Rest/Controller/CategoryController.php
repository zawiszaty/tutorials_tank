<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\Category\ChangeName\ChangeNameCommand;
use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Category\Delete\DeleteCategoryCommand;
use App\Application\Query\Category\GetAll\GetAllCommand;
use App\Application\Query\Category\GetSingle\GetSingleCommand;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\UI\HTTP\Common\Controller\RestController;
use App\UI\HTTP\Common\Form\CategoryType;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class CategoryController.
 */
class CategoryController extends RestController
{
    /**
     * @param Request $request
     *
     * @return Response
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
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="name", type="string"),
     *     )
     * )
     * @SWG\Tag(name="Category")
     * @NelmioSecurity(name="BearerAdmin")
     */
    public function createCategoryAction(Request $request): Response
    {
        $command = new CreateCategoryCommand();
        $form = $this->createForm(CategoryType::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new JsonResponse('success', Response::HTTP_OK);
        }

        return new Response($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     *
     * @SWG\Response(
     *     response=200,
     *     description="success edit"
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
     *         @SWG\Property(property="name", type="string"),
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     type="string",
     *     in="path",
     * )
     * @SWG\Tag(name="Category")
     * @NelmioSecurity(name="BearerAdmin")
     */
    public function changeNameAction(Request $request, string $id): Response
    {
        $command = new ChangeNameCommand();
        $command->id = $id;
        $form = $this->createForm(CategoryType::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new JsonResponse('success', Response::HTTP_OK);
        }

        return new Response($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="success edit"
     * )
     * @SWG\Response(
     *     response=404,
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
     * @SWG\Tag(name="Category")
     * @NelmioSecurity(name="BearerAdmin")
     */
    public function deleteCategoryAction(Request $request, string $id): Response
    {
        $command = new DeleteCategoryCommand($id);
        $this->commandBus->handle($command);

        return new JsonResponse('', Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Response(
     *     response=200,
     *     description="All Category in Database"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not Found"
     * )
     * @SWG\Tag(name="Category")
     */
    public function getAllCategoryAction(Request $request): Response
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
     * @param string  $id
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return Response
     *
     * @SWG\Response(
     *     response=200,
     *     description="success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not Found"
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     type="string",
     *     in="path",
     * )
     * @SWG\Tag(name="Category")
     */
    public function getSingleAction(Request $request, string $id): Response
    {
        $aggregateRootId = AggregateRootId::fromString($id);
        $command = new GetSingleCommand($aggregateRootId);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }
}
