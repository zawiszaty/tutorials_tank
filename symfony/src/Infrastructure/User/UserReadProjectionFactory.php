<?php

/** @noinspection PhpMissingParentConstructorInspection */

namespace App\Infrastructure\User;

use App\Domain\User\Event\UserAvatarWasChanged;
use App\Domain\User\Event\UserMailWasChanged;
use App\Domain\User\Event\UserNameWasChanged;
use App\Domain\User\Event\UserPasswordWasChanged;
use App\Domain\User\Event\UserUnBann;
use App\Domain\User\Event\UserWasAdminRoleGranted;
use App\Domain\User\Event\UserWasAdminUnGranted;
use App\Domain\User\Event\UserWasBanned;
use App\Domain\User\Event\UserWasConfirmed;
use App\Domain\User\Event\UserWasCreated;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use App\Infrastructure\User\Repository\UserRepositoryElastic;
use Broadway\ReadModel\Projector;

/**
 * Class UserReadProjectionFactory.
 */
final class UserReadProjectionFactory extends Projector
{
    /**
     * @var MysqlUserReadModelRepository
     */
    private $repository;

    /**
     * @var UserRepositoryElastic
     */
    private $userRepositoryElastic;

    /**
     * UserReadProjectionFactory constructor.
     */
    public function __construct(MysqlUserReadModelRepository $repository, UserRepositoryElastic $userRepositoryElastic)
    {
        $this->repository = $repository;
        $this->userRepositoryElastic = $userRepositoryElastic;
    }

    /**
     * @throws \Exception
     */
    public function applyUserWasCreated(UserWasCreated $userWasCreated)
    {
        $userView = UserView::deserializeProjections($userWasCreated->serialize());
//        if ($userView->isBanned()) {
//            $userView->unBan();
//        }
        $this->repository->add($userView);
        $this->userRepositoryElastic->store($userView->serializeProjections());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserWasConfirmed(UserWasConfirmed $userWasConfirmed)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($userWasConfirmed->getId());
        $userView->confirmed();
        $this->repository->apply();
        $this->userRepositoryElastic->edit($userView->serializeProjections());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserWasBanned(UserWasBanned $userWasBanned)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($userWasBanned->getId());
        $userView->banned();
        $this->repository->apply();
        $this->userRepositoryElastic->edit($userView->serializeProjections());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserNameWasChanged(UserNameWasChanged $event)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($event->getId());
        $userView->changeName($event->getUsername()->toString());
        $this->repository->apply();
        $this->userRepositoryElastic->edit($userView->serializeProjections());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserMailWasChanged(UserMailWasChanged $event)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($event->getId());
        $userView->changeMail($event->getEmail()->toString());
        $this->repository->apply();
        $this->userRepositoryElastic->edit($userView->serializeProjections());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserPasswordWasChanged(UserPasswordWasChanged $event)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($event->getId());
        $userView->changePassword($event->getPassword()->toString());
        $this->repository->apply();
        $this->userRepositoryElastic->edit($userView->serializeProjections());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserAvatarWasChanged(UserAvatarWasChanged $event)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($event->getId());
        $userView->changeAvatar($event->getAvatar()->toString());
        $this->repository->apply();
        $this->userRepositoryElastic->edit($userView->serializeProjections());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserWasAdminRoleGranted(UserWasAdminRoleGranted $adminRoleGranted): void
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($adminRoleGranted->getId());
        $userView->appendRole('ROLE_ADMIN');
        $this->repository->apply();
        $this->userRepositoryElastic->edit($userView->serializeProjections());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function applyUserWasAdminUnGranted(UserWasAdminUnGranted $userWasAdminUnGranted): void
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($userWasAdminUnGranted->getId());
        $userView->unAppendRole('ROLE_ADMIN');
        $this->repository->apply();
        $this->userRepositoryElastic->edit($userView->serializeProjections());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyUserUnBann(UserUnBann $userUnBann)
    {
        /** @var UserView $userView */
        $userView = $this->repository->oneByUuid($userUnBann->getId());
        $userView->unBan();
        $this->repository->apply();
        $this->userRepositoryElastic->edit($userView->serializeProjections());
    }
}
