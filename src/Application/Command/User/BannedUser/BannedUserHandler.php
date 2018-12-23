<?php

namespace App\Application\Command\User\BannedUser;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Assert\UserIsBanned;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class BannedUserHandler.
 */
class BannedUserHandler implements CommandHandlerInterface
{
    /**
     * @var \App\Infrastructure\User\Repository\UserRepository
     */
    private $aggregatRepository;
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * ConfirmUserHandler constructor.
     *
     * @param \App\Infrastructure\User\Repository\UserRepository $aggregatRepository
     */
    public function __construct(\App\Infrastructure\User\Repository\UserRepository $aggregatRepository, TokenStorage $tokenStorage)
    {
        $this->aggregatRepository = $aggregatRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param BannedUserCommand $command
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(BannedUserCommand $command): void
    {
        if ($this->tokenStorage->getToken()->getUser()->getId() === $command->getId()) {
            throw new \Exception('Nie mozesz zbanować sam siebie');
        }
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($command->getId()));
        UserIsBanned::check($user);
        $user->banned();
        $this->aggregatRepository->store($user);
    }
}
