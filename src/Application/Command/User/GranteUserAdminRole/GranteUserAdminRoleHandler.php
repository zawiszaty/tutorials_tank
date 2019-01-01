<?php

namespace App\Application\Command\User\GranteUserAdminRole;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use App\Infrastructure\User\Repository\UserRepository;

class GranteUserAdminRoleHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var UserRepository
     */
    private $aggregatRepository;

    /**
     * ConfirmUserHandler constructor.
     *
     * @param MysqlUserReadModelRepository $repository
     * @param UserRepository               $aggregatRepository
     */
    public function __construct(MysqlUserReadModelRepository $repository, UserRepository $aggregatRepository)
    {
        $this->repository = $repository;
        $this->aggregatRepository = $aggregatRepository;
    }

    /**
     * @param GranteUserAdminRoleCommand $granteUserAdminRoleCommand
     *
     * @throws \App\Domain\User\Exception\UserIsAdminException
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(GranteUserAdminRoleCommand $granteUserAdminRoleCommand)
    {
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($granteUserAdminRoleCommand->userId));
        $user->grantedAdminRole();
    }
}
