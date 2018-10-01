<?php

namespace App\Infrastructure\User;

use App\Domain\User\Event\UserWasBanned;
use App\Domain\User\Event\UserWasConfirmed;
use App\Domain\User\Event\UserWasCreated;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Broadway\ReadModel\Projector;

/**
 * Class UserReadProjectionFactory
 * @package App\Infrastructure\User
 */
class UserReadProjectionFactory extends Projector
{
    /**
     * @var MysqlUserReadModelRepository
     */
    private $repository;

    /**
     * UserReadProjectionFactory constructor.
     *
     * @param MysqlUserReadModelRepository $repository
     */
    public function __construct(MysqlUserReadModelRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UserWasCreated $userWasCreated
     * @throws \Exception
     */
    public function applyUserWasCreated(UserWasCreated $userWasCreated)
    {
        $userView = UserView::deserializeProjections($userWasCreated->serialize());
        $this->repository->add($userView);
    }

    /**
     * @param UserWasConfirmed $userWasConfirmed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserWasConfirmed(UserWasConfirmed $userWasConfirmed)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($userWasConfirmed->getId());
        $userView->confirmed();
        $this->repository->apply();
    }

    /**
     * @param UserWasBanned $userWasBanned
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserWasBanned(UserWasBanned $userWasBanned)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($userWasBanned->getId());
        $userView->banned();
        $this->repository->apply();
    }
}
