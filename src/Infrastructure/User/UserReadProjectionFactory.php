<?php

namespace App\Infrastructure\User;


use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\User;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Broadway\ReadModel\Projector;

class UserReadProjectionFactory extends Projector
{
    /**
     * @var MysqlUserReadModelRepository
     */
    private $repository;

    /**
     * UserReadProjectionFactory constructor.
     * @param MysqlUserReadModelRepository $repository
     */
    public function __construct(MysqlUserReadModelRepository $repository)
    {
        $this->repository = $repository;
    }

    public function applyUserWasCreated(UserWasCreated $userWasCreated)
    {
        $userView = UserView::deserializeProjections($userWasCreated->serialize());
        $this->repository->add($userView);
    }
}