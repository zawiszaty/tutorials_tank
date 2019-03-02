<?php

namespace App\Application\Command\User\PasswordRecover;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class ChangePasswordHandler.
 */
class PasswordRecoverHandler implements CommandHandlerInterface
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
    public function __invoke(PasswordRecoverCommand $command): void
    {
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($command->id));
        $user->changePassword(PasswordEncoder::encode($command->plainPassword));
        $this->aggregatRepository->store($user);
    }
}
