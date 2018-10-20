<?php

namespace App\UI\HTTP\Rest\Controller;

use App\Application\Command\User\BannedUser\BannedUserCommand;
use App\Application\Command\User\ChangeAvatar\ChangeAvatarCommand;
use App\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use App\Application\Command\User\ChangeName\ChangeUserNameCommand;
use App\Application\Command\User\ChangePassword\ChangePasswordCommand;
use App\Application\Command\User\ConfirmUser\ConfirmUserCommand;
use App\Application\Command\User\Create\CreateUserCommand;
use App\Application\Command\User\SendEmail\SendEmailCommand;
use App\Application\Query\Item;
use App\Application\Query\User\GetAll\GetAllCommand;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Exception\AvatarWasChanged;
use App\Domain\User\Exception\PasswordIsBadException;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use App\UI\HTTP\Common\Form\ChangeAvatarForm;
use App\UI\HTTP\Common\Form\ChangeEmailForm;
use App\UI\HTTP\Common\Form\ChangePasswordForm;
use App\UI\HTTP\Common\Form\ChangeUserNameForm;
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
                /** @var Item $user */
                $user = $this->userReadModelRepository->getSingle(AggregateRootId::fromString($exception->getMessage()));

                $response = new JsonResponse([
                    'id' => $exception->getMessage(),
                    'token' => $user->readModel->getConfirmationToken(),
                ], 200);

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

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Assert\AssertionFailedException
     */
    public function changeNameAction(Request $request): Response
    {
        $command = new ChangeUserNameCommand();
        $command->setId(AggregateRootId::fromString($this->getUser()->getId()));
        $form = $this->createForm(ChangeUserNameForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            $response = new JsonResponse('success', 200);

            return $response;
        }

        $response = new JsonResponse('error', JsonResponse::HTTP_BAD_REQUEST);

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Assert\AssertionFailedException
     */
    public function changeEmailAction(Request $request): Response
    {
        $command = new ChangeEmailCommand();
        $command->setId(AggregateRootId::fromString($this->getUser()->getId()));
        $form = $this->createForm(ChangeEmailForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            $sendEmailCommand = new SendEmailCommand($command->getEmail(), $this->getUser()->getConfirmationToken());
            $this->commandBus->handle($sendEmailCommand);
            $response = new JsonResponse('success', 200);

            return $response;
        }

        $response = new JsonResponse('error', JsonResponse::HTTP_BAD_REQUEST);

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function changePasswordAction(Request $request): Response
    {
        $command = new ChangePasswordCommand();
        $command->setId($this->getUser()->getId());

        $form = $this->createForm(ChangePasswordForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            if (
            !password_verify(
                $command->getOldPassword(),
                $this->getUser()->getPassword()
            )
            ) {
                throw new PasswordIsBadException();
            }
            $this->commandBus->handle($command);
            $response = new JsonResponse('success', JsonResponse::HTTP_OK);

            return $response;
        }

        $response = new JsonResponse('error', JsonResponse::HTTP_BAD_REQUEST);

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAllAction(Request $request)
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        if ($request->get('query')) {
            $query = [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'wildcard' => [
                                    'username' => '*' . $request->get('query') . '*',
                                ],
                            ],
                            [
                                'wildcard' => [
                                    'email' => '*' . $request->get('query') . '*',
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        } else {
            $query = [];
        }

        $command = new GetAllCommand($page, $limit, $query);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function changeAvatar(Request $request): Response
    {
        $command = new ChangeAvatarCommand();
        $command->setId($this->getUser()->getId());

        $form = $this->createForm(ChangeAvatarForm::class, $command);
        $file = $request->files->get('file');
        $request->request->set('file', $file);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($command);
            } catch (AvatarWasChanged $exception) {
                $response = new JsonResponse([
                    'avatar' => $exception->getMessage(),
                ], JsonResponse::HTTP_OK);

                return $response;
            }
        }
        $response = new JsonResponse($this->getErrorMessages($form), JsonResponse::HTTP_BAD_REQUEST);

        return $response;
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
