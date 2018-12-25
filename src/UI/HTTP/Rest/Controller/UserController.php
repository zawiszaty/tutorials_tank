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
use App\Application\Query\User\GetAll\GetAllCommand;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Exception\AvatarWasChanged;
use App\UI\HTTP\Common\Controller\RestController;
use App\UI\HTTP\Common\Form\ChangeAvatarForm;
use App\UI\HTTP\Common\Form\ChangeEmailForm;
use App\UI\HTTP\Common\Form\ChangePasswordForm;
use App\UI\HTTP\Common\Form\ChangeUserNameForm;
use App\UI\HTTP\Common\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;
use Swagger\Annotations as SWG;

/**
 * Class UserController.
 */
class UserController extends RestController
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
     *     response=401,
     *     description="add token"
     * )
     *
     * @SWG\Parameter(
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="username", type="string"),
     *         @SWG\Property(property="plainPassword", type="array", @SWG\Items(type="object",@SWG\Property(property="first", type="string"),@SWG\Property(property="second", type="string"))),
     *     )
     * )
     * @SWG\Tag(name="User")
     */
    public function createCategoryAction(Request $request): Response
    {
        $command = new CreateUserCommand();
        $form = $this->createForm(RegistrationFormType::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new JsonResponse('success', Response::HTTP_CREATED);
        }

        return new JsonResponse($this->getErrorMessages($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param string  $token
     *
     * @return Response
     *
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     * @SWG\Tag(name="User")
     */
    public function confirmUserAction(Request $request, string $token): Response
    {
        $command = new ConfirmUserCommand($token);
        $this->commandBus->handle($command);
        $response = new JsonResponse('success', Response::HTTP_OK);

        return $response;
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     * @SWG\Tag(name="User")
     * @NelmioSecurity(name="BearerAdmin")
     */
    public function bannedUserAction(Request $request, string $id): Response
    {
        $command = new BannedUserCommand($id);
        $this->commandBus->handle($command);

        return new JsonResponse('success', Response::HTTP_OK);
    }

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
     *     response=401,
     *     description="add token"
     * )
     * @SWG\Tag(name="User")
     *
     * @SWG\Parameter(
     *     name="name",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="name", type="string"),
     *     )
     * )
     *
     * @NelmioSecurity(name="BearerUser")
     */
    public function changeNameAction(Request $request): Response
    {
        $command = new ChangeUserNameCommand();
        $command->id = $this->getUser()->getId();
        $form = $this->createForm(ChangeUserNameForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);

            return new JsonResponse('success', Response::HTTP_OK);
        }
        $response = new JsonResponse($this->getErrorMessages($form), JsonResponse::HTTP_BAD_REQUEST);

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Assert\AssertionFailedException
     *
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     * @SWG\Tag(name="User")
     *
     * @SWG\Parameter(
     *     name="name",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="email", type="email"),
     *     )
     * )
     *
     * @NelmioSecurity(name="BearerUser")
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="success create"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="add token"
     * )
     * @SWG\Tag(name="User")
     *
     * @SWG\Parameter(
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="plainPassword", type="array", @SWG\Items(type="object",@SWG\Property(property="first", type="string"),@SWG\Property(property="second", type="string"))),
     *     )
     * )
     *
     * @NelmioSecurity(name="BearerUser")
     */
    public function changePasswordAction(Request $request): Response
    {
        $command = new ChangePasswordCommand();
        $command->id = $this->getUser()->getId();
        $command->currentPassword = $this->getUser()->getPassword();
        $form = $this->createForm(ChangePasswordForm::class, $command);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle($command);
            $response = new JsonResponse('success', JsonResponse::HTTP_OK);

            return $response;
        }

        $response = new JsonResponse($this->getErrorMessages($form), JsonResponse::HTTP_BAD_REQUEST);

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
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
     * @SWG\Tag(name="User")
     */
    public function getAllAction(Request $request)
    {
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $query = $request->get('query') ?? 10;
        $command = new GetAllCommand($page, $limit, $query);
        $model = $this->queryBus->handle($command);

        return new JsonResponse($model, 200);
    }

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
     *     response=401,
     *     description="add token"
     * )
     * @SWG\Tag(name="User")
     *
     * @SWG\Parameter(
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="file", type="file"),
     *     )
     * )
     *
     * @NelmioSecurity(name="BearerUser")
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
}
