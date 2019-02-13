<?php

namespace App\Application\Command\User\ChangeName;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class ChangeUserNameHandler.
 */
class ChangeUserNameHandler implements CommandHandlerInterface
{
    /**
     * @var \App\Infrastructure\User\Repository\UserRepository
     */
    private $aggregatRepository;

    /**
     * ConfirmUserHandler constructor.
     */
    public function __construct(\App\Infrastructure\User\Repository\UserRepository $aggregatRepository)
    {
        $this->aggregatRepository = $aggregatRepository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(ChangeUserNameCommand $command): void
    {
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($command->id));
        $user->changeName($command->username);
        $this->aggregatRepository->store($user);
    }
}
