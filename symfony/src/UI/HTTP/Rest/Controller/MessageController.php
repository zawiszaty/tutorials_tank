<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Query\Message\GetAll\GetAllCommand;
use App\UI\HTTP\Common\Controller\RestController;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MessageController.
 */
class MessageController extends RestController
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
     * @SWG\Tag(name="Message")
     * @NelmioSecurity(name="BearerUser")
     */
    public function getAllUserMessage(Request $request): Response
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $recipient = $request->get('recipient');
        $query = $request->get('query');
        $command = new GetAllCommand($page, $limit, $query, $recipient, $this->getUser()->getId());
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }
}
