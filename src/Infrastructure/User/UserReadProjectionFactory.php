<?php

namespace App\Infrastructure\User;

use App\Domain\User\Event\UserAvatarWasChanged;
use App\Domain\User\Event\UserMailWasChanged;
use App\Domain\User\Event\UserNameWasChanged;
use App\Domain\User\Event\UserPasswordWasChanged;
use App\Domain\User\Event\UserWasBanned;
use App\Domain\User\Event\UserWasConfirmed;
use App\Domain\User\Event\UserWasCreated;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Broadway\ReadModel\Projector;

/**
 * Class UserReadProjectionFactory.
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
     *
     * @throws \Exception
     */
    public function applyUserWasCreated(UserWasCreated $userWasCreated)
    {
        $userView = UserView::deserializeProjections($userWasCreated->serialize());
        $this->repository->add($userView);
    }

    /**
     * @param UserWasConfirmed $userWasConfirmed
     *
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
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserWasBanned(UserWasBanned $userWasBanned)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($userWasBanned->getId());
        $userView->banned();
        $this->repository->apply();
    }

    /**
     * @param UserNameWasChanged $event
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserNameWasChanged(UserNameWasChanged $event)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($event->getId());
        $userView->changeName($event->getUsername()->toString());
        $this->repository->apply();
    }

    /**
     * @param UserMailWasChanged $event
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserMailWasChanged(UserMailWasChanged $event)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($event->getId());
        $userView->changeMail($event->getEmail()->toString());
        $this->repository->apply();
    }

    public function applyUserPasswordWasChanged(UserPasswordWasChanged $event)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($event->getId());
        $userView->changePassword($event->getPassword()->toString());
        $this->repository->apply();
    }

    public function applyUserAvatarWasChanged(UserAvatarWasChanged $event)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($event->getId());
        $userView->changeAvatar($event->getAvatar()->toString());
        $this->repository->apply();
    }
}
