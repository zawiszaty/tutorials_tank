<?php

namespace App\Application\Command\User\Create;

use App\Application\Command\CommandHandlerInterface;

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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getSteemit()
    {
        return $this->steemit;
    }

    /**
     * @param mixed $steemit
     */
    public function setSteemit($steemit): void
    {
        $this->steemit = $steemit;
    }

    /**
     * @return mixed
     */
    public function getBanned()
    {
        return $this->banned;
    }

    /**
     * @param boolean $banned
     */
    public function setBanned($banned): void
    {
        $this->banned = $banned;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
