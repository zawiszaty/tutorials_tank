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
     *
     * @param \App\Infrastructure\User\Repository\UserRepository $aggregatRepository
     */
    public function __construct(\App\Infrastructure\User\Repository\UserRepository $aggregatRepository)
    {
        $this->aggregatRepository = $aggregatRepository;
    }

    /**
     * @param ChangeUserNameCommand $command
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(ChangeUserNameCommand $command): void
    {
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($command->getId()));
        $user->changeName($command->getName());
        $this->aggregatRepository->store($user);
    }
}
