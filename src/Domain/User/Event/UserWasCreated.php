<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\ValueObject\Avatar;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Roles;
use App\Domain\User\ValueObject\Steemit;
use App\Domain\User\ValueObject\UserName;

/**
 * Class UserWasCreated.
 */
class UserWasCreated extends AbstractEvent
{
    /**
     * @var AggregateRootId
     */
    protected $id;

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
     * User constructor.
     *
     * @param AggregateRootId $id
     * @param UserName        $username
     * @param Email           $email
     * @param Roles           $roles
     * @param Avatar          $avatar
     * @param Steemit         $steemit
     * @param bool            $banned
     * @param Password        $password
     */
    public function __construct(AggregateRootId $id, UserName $username, Email $email, Roles $roles, Avatar $avatar, Steemit $steemit, bool $banned, Password $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->roles = $roles;
        $this->avatar = $avatar;
        $this->steemit = $steemit;
        $this->banned = $banned;
        $this->password = $password;
    }

    /**
     * @param array $data
     *
     * @return UserWasCreated|mixed
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data)
    {
        return new self(
            AggregateRootId::fromString($data['id']),
            UserName::fromString($data['userName']),
            Email::fromString($data['email']),
            Roles::fromString($data['roles']),
            Avatar::fromString($data['avatar']),
            Steemit::fromString($data['steemit']),
            $data['bool']
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id'       => $this->id->toString(),
            'username' => $this->username->toString(),
            'email'    => $this->email->toString(),
            'roles'    => $this->roles->toArray(),
            'avatar'   => $this->avatar->toString(),
            'steemit'  => $this->steemit->toString(),
            'banned'   => $this->banned,
            'password' => $this->password->toString(),
        ];
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
}
