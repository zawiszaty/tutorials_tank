<?php

namespace App\Application\Command\User\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\User\SendEmail\SendEmailCommand;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Factory\UserFactory;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Avatar;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Roles;
use App\Domain\User\ValueObject\Steemit;
use App\Domain\User\ValueObject\UserName;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use App\Infrastructure\User\Repository\UserRepository;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;

/**
 * Class CreateUserHandler.
 */
class CreateUserHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var MysqlUserReadModelRepository
     */
    private $mysqlUserReadModelRepository;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * CreateUserHandler constructor.
     */
    public function __construct(
        UserRepository $repository,
        MysqlUserReadModelRepository $mysqlUserReadModelRepository,
        CommandBus $commandBus
    ) {
        $this->repository = $repository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function __invoke(CreateUserCommand $command)
    {
        /** @var User $user */
        $user = UserFactory::create(
            AggregateRootId::fromString(Uuid::uuid4()),
            UserName::fromString($command->getUsername()),
            Email::fromString($command->getEmail()),
            Roles::fromString($command->getRoles()),
            Avatar::fromString($command->getAvatar()),
            Steemit::fromString($command->getSteemit()),
            $command->getBanned(),
            Password::fromString($command->getPlainPassword())
        );
        $this->repository->store($user);
        $sendEmailCommand = new SendEmailCommand($command->getEmail(), $user->getConfirmationToken()->toString());
        $this->commandBus->handle($sendEmailCommand);
    }
}
