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
class UserNameWasChanged extends AbstractEvent
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
     * @var bool
     */
    private $enabled;

    /**
     * User constructor.
     */
    public function __construct(AggregateRootId $id, UserName $username, Email $email, Roles $roles, Avatar $avatar, Steemit $steemit, bool $banned, bool $enabled)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->roles = $roles;
        $this->avatar = $avatar;
        $this->steemit = $steemit;
        $this->banned = $banned;
        $this->enabled = $enabled;
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return UserWasCreated|mixed
     */
    public static function deserialize(array $data)
    {
        return new self(
            AggregateRootId::fromString($data['id']),
            UserName::fromString($data['username']),
            Email::fromString($data['email']),
            Roles::fromString($data['roles']),
            Avatar::fromString($data['avatar']),
            Steemit::fromString($data['steemit']),
            $data['banned'],
            $data['enabled']
        );
    }

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
            'enabled'  => $this->enabled,
        ];
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getUsername(): UserName
    {
        return $this->username;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRoles(): Roles
    {
        return $this->roles;
    }

    public function getAvatar(): Avatar
    {
        return $this->avatar;
    }

    public function getSteemit(): Steemit
    {
        return $this->steemit;
    }

    public function isBanned(): bool
    {
        return $this->banned;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
