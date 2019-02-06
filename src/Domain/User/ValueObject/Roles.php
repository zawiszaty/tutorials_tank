<?php

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

/**
 * Class Roles
 *
 * @package App\Domain\User\ValueObject
 */
class Roles
{
    /**
     * @var array
     */
    private $roles;

    /**
     * @param array $roles
     *
     * @return Roles
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(array $roles): self
    {
        Assertion::isArray($roles);

        $instance = new self();
        $instance->roles = $roles;

        return $instance;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->roles;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return $this->roles;
    }

    /**
     * @param string $role
     *
     * @throws \Exception
     */
    public function appendRole(string $role)
    {
        if (in_array($role, $this->roles)) {
            throw new \Exception();
        }

        $this->roles[] = $role;
    }
}
