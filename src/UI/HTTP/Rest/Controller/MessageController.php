<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Query\Message\GetAll\GetAllCommand;
use App\UI\HTTP\Common\Controller\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MessageController.
 */
class MessageController extends RestController
{
    /**
     * @param Request $request
     *
     * @return Response
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
