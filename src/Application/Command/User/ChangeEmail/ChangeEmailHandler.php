<?php

namespace App\Application\Command\User\ChangeEmail;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class ChangeEmailHandler
 *
 * @package App\Application\Command\User\ChangeEmail
 */
class ChangeEmailHandler implements CommandHandlerInterface
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
     * @param ChangeEmailCommand $command
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(ChangeEmailCommand $command): void
    {
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($command->getId()));
        $user->changeEmail($command->getEmail());
        $this->aggregatRepository->store($user);
    }
}
