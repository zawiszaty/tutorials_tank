<?php

namespace App\Application\Command\User\UnGranteUserAdminRole;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use App\Infrastructure\User\Repository\UserRepository;

/**
 * Class GranteUserAdminRoleHandler.
 */
class UnGranteUserAdminRoleHandler implements CommandHandlerInterface
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
     */
    public function __construct(MysqlUserReadModelRepository $repository, UserRepository $aggregatRepository)
    {
        $this->repository = $repository;
        $this->aggregatRepository = $aggregatRepository;
    }

    /**
     * @throws \App\Domain\User\Exception\UserIsAdminException
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(UnGranteUserAdminRoleCommand $unGranteUserAdminRoleCommand)
    {
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($unGranteUserAdminRoleCommand->userId));
        $user->unGrantedAdminRole();
        $this->aggregatRepository->store($user);
    }
}
