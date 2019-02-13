<?php

namespace App\Application\Command\User\ConfirmUser;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Assert\UserIsBanned;
use App\Domain\User\Assert\UserIsEnabled;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use App\Infrastructure\User\Query\Repository\UserRepository;

/**
 * Class ConfirmUserHandler.
 */
class ConfirmUserHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var \App\Infrastructure\User\Repository\UserRepository
     */
    private $aggregatRepository;

    /**
     * ConfirmUserHandler constructor.
     */
    public function __construct(MysqlUserReadModelRepository $repository, \App\Infrastructure\User\Repository\UserRepository $aggregatRepository)
    {
        $this->repository = $repository;
        $this->aggregatRepository = $aggregatRepository;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(ConfirmUserCommand $command): void
    {
        $id = AggregateRootId::fromString($this->repository->getByToken($command->getToken())->getId());
        $user = $this->aggregatRepository->get($id);
        UserIsBanned::check($user);
        UserIsEnabled::check($user);
        $user->confirm();

        $this->aggregatRepository->store($user);
    }
}
