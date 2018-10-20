<?php

namespace App\Application\Command\User\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Factory\UserFactory;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Avatar;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Roles;
use App\Domain\User\ValueObject\Steemit;
use App\Domain\User\ValueObject\UserName;
use App\Infrastructure\User\Repository\UserRepository;
use Ramsey\Uuid\Uuid;

class CreateUserHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateUserCommand $command
     *
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

        throw new \Exception($user->getId()->toString());
    }
}
