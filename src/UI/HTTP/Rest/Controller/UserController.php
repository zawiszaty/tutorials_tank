<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\User\Create\CreateUserCommand;
use App\Domain\User\User;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use App\UI\HTTP\Common\Form\RegistrationFormType;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\Dbal\DBALEventStore;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\UI\HTTP\Rest\Controller
 * @Route("/api")
 */
class UserController extends Controller
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
     * @var MysqlUserReadModelRepository
     */
    private $userReadModelRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        CommandBus $queryBus,
        CommandBus $commandBus,
        EventBus $eventBus,
        DBALEventStore $eventStore,
        MysqlUserReadModelRepository $userReadModelRepository,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->eventStore = $eventStore;
        $this->userReadModelRepository = $userReadModelRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/v1/user/register", name="add_user", methods="POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createCategoryAction(Request $request): Response
    {
        $command = new CreateUserCommand();
        $form = $this->createForm(RegistrationFormType::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($command);
            } catch (\Exception $exception) {
                $response = new Response('dziala', 200);
                dump($exception->getMessage());
                die();
                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($exception->getMessage(), $request, $response));
                return $response;
            }
        }

        return new Response('nie dziala', Response::HTTP_BAD_REQUEST);
    }
}