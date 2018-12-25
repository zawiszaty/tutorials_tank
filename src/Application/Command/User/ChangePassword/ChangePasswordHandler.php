<?php

namespace App\Application\Command\User\ChangePassword;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Share\Application\Password\PasswordVerify;

/**
 * Class ChangePasswordHandler
 * @package App\Application\Command\User\ChangePassword
 */
class ChangePasswordHandler implements CommandHandlerInterface
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
     * @param ChangePasswordCommand $command
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(ChangePasswordCommand $command): void
    {
        PasswordVerify::verify($command->oldPassword, $command->currentPassword);
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($command->id));
        $user->changePassword($command->plainPassword);
        $this->aggregatRepository->store($user);
    }
}
