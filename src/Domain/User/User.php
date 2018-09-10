<?php

namespace App\Domain\User;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\ValueObject\Avatar;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Roles;
use App\Domain\User\ValueObject\Steemit;
use App\Domain\User\ValueObject\UserName;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

/**
 * Class User.
 */
class User extends EventSourcedAggregateRoot
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * @var UserName
     */
    private $username;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var Roles
     */
    private $roles;

    /**
     * @var Avatar
     */
    private $avatar;

    /**
     * @var Steemit
     */
    private $steemit;

    /**
     * @var bool
     */
    private $banned;

    /**
     * @var Password
     */
    private $password;

    /**
     * @param AggregateRootId $id
     * @param UserName        $username
     * @param Email           $email
     * @param Roles           $roles
     * @param Avatar          $avatar
     * @param Steemit         $steemit
     * @param bool            $banned
     * @param Password        $password
     *
     * @return mixed
     */
    public static function create(AggregateRootId $id, UserName $username, Email $email, Roles $roles, Avatar $avatar, Steemit $steemit, bool $banned, Password $password)
    {
        $user = new self();
        $user->apply(new UserWasCreated($id, $username, $email, $roles, $avatar, $steemit, $banned, $password));

        return $user;
    }

    /**
     * @param UserWasCreated $userWasCreated
     */
    public function applyUserWasCreated(UserWasCreated $userWasCreated): void
    {
        $this->id = $userWasCreated->getId();
        $this->username = $userWasCreated->getUsername();
        $this->email = $userWasCreated->getEmail();
        $this->roles = $userWasCreated->getRoles();
        $this->avatar = $userWasCreated->getAvatar();
        $this->steemit = $userWasCreated->getSteemit();
        $this->banned = $userWasCreated->isBanned();
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return UserName
     */
    public function getUsername(): UserName
    {
        return $this->username;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Roles
     */
    public function getRoles(): Roles
    {
        return $this->roles;
    }

    /**
     * @return Avatar
     */
    public function getAvatar(): Avatar
    {
        return $this->avatar;
    }

    /**
     * @return Steemit
     */
    public function getSteemit(): Steemit
    {
        return $this->steemit;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->banned;
    }

    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }
}
