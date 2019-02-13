<?php

namespace App\Application\Command\User\Create;

use App\Application\Command\CommandHandlerInterface;

/**
 * Class CreateUserCommand.
 */
class CreateUserCommand implements CommandHandlerInterface
{
    /**
     * @var mixed
     */
    public $id;

    /**
     * @var mixed
     */
    public $username;

    /**
     * @var mixed
     */
    public $email;

    /**
     * @var mixed
     */
    public $roles;

    /**
     * @var mixed
     */
    public $avatar;

    /**
     * @var mixed
     */
    public $steemit;

    /**
     * @var mixed
     */
    public $banned;

    /**
     * @var mixed
     */
    public $plainPassword;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getSteemit()
    {
        return $this->steemit;
    }

    public function setSteemit($steemit): void
    {
        $this->steemit = $steemit;
    }

    public function getBanned()
    {
        return $this->banned;
    }

    /**
     * @param bool $banned
     */
    public function setBanned($banned): void
    {
        $this->banned = $banned;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
