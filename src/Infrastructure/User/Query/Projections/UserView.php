<?php

namespace App\Infrastructure\User\Query\Projections;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * Class UserView
 * @package App\Infrastructure\User\Query\Projections
 */
class UserView implements UserInterface, EquatableInterface, \Serializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var string
     */
    private $steemit;

    /**
     * @var array
     */
    private $roles = ['ROLE_USER'];

    /**
     * @var string
     */
    private $salt;

    /**
     * @var string
     */
    private $token;

    /**
     * @var bool
     */
    private $confirmed;

    /**
     * @var bool
     */
    private $banned;

    /**
     * UserView constructor.
     * @param string $id
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $avatar
     * @param string $steemit
     * @param string $salt
     * @param array $roles
     * @param string $token
     * @param bool $confirmed
     * @param bool $banned
     */
    public function __construct(string $id, string $username, string $email, string $password, string $avatar, string $steemit, string $salt, ?array $roles, string $token, bool $confirmed, bool $banned)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar;
        $this->steemit = $steemit;
        $this->token = $token;
        $this->confirmed = $confirmed;
        $this->banned = $banned;
        $this->salt = $salt;
        $this->roles[] = $roles;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function getSteemit(): string
    {
        return $this->steemit;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->banned;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof UserView) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->email,
            $this->avatar,
            $this->steemit,
            $this->token,
            $this->confirmed,
            $this->banned,
            $this->salt,
            $this->roles,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->avatar,
            $this->steemit,
            $this->token,
            $this->confirmed,
            $this->banned,
            ) = unserialize($serialized, array('allowed_classes' => false));
    }
}