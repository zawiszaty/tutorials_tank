<?php

namespace App\Application\Command\User\UnBannedUser;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class BannedUserHandler.
 */
class UnBannedUserHandler implements CommandHandlerInterface
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
    public function __invoke(UnBannedUserCommand $command): void
    {
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($command->getId()));
        $user->unBann();
        $this->aggregatRepository->store($user);
    }
}
