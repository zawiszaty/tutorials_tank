<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\Notification\View\ViewNotificationCommand;
use App\Application\Query\Notification\GetAllByUser\GetAllByUserCommand;
use App\UI\HTTP\Common\Controller\RestController;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MessageController.
 */
final class NotificationController extends RestController
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
     * @SWG\Tag(name="Notification")
     */
    public function getAllByUser(Request $request): Response
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $query = $request->get('query');
        $sort = $request->get('sort') ?? 'desc';
        $command = new GetAllByUserCommand($page, $limit, $sort, $query);
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
     * @SWG\Tag(name="Notification")
     * @NelmioSecurity(name="BearerUser")
     */
    public function viewNotifications(Request $request): Response
    {
        $command = new ViewNotificationCommand();
        $command->notifications = $request->request->get('notifications');
        $this->commandBus->handle($command);

        return new JsonResponse('success', Response::HTTP_OK);
    }
}
