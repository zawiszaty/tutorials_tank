<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\User\BannedUser\BannedUserCommand;
use App\Application\Command\User\ConfirmUser\ConfirmUserCommand;
use App\Application\Command\User\Create\CreateUserCommand;
use App\Application\Command\User\SendEmail\SendEmailCommand;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use App\UI\HTTP\Common\Form\RegistrationFormType;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\Dbal\DBALEventStore;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 *
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
    ) {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->eventStore = $eventStore;
        $this->userReadModelRepository = $userReadModelRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Doctrine\ORM\NonUniqueResultException
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
                /** @var UserView $user */
                $user = $this->userReadModelRepository->oneByEmail(Email::fromString($command->getEmail()));
                $sendEmailCommand = new SendEmailCommand($command->getEmail(), $user->getConfirmationToken());
                $this->commandBus->handle($sendEmailCommand);
                $response = new JsonResponse('success', 200);

                return $response;
            }
        }

        return new JsonResponse('error', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param string  $token
     *
     * @return Response
     */
    public function confirmUserAction(Request $request, string $token): Response
    {
        $command = new ConfirmUserCommand($token);

        $this->commandBus->handle($command);
        $response = new JsonResponse('success', 200);

        return $response;
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function bannedUserAction(Request $request, string $id): Response
    {
        if ($this->getUser()->getId() === $id) {
            throw new \Exception('Nie mozesz zbanować sam siebie');
        }
        $command = new BannedUserCommand($id);
        $this->commandBus->handle($command);

        $response = new JsonResponse('success', 200);

        return $response;
    }
}
