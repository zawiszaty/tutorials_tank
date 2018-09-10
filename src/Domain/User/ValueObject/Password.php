<?php

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

class Password
{
    /**
     * @var null|string
     */
    private $password;

    /**
     * @param string $password
     * @return password
     */
    public static function fromString(string $password): self
    {
        $instance = new self();
        $instance->password = password_hash($password, PASSWORD_DEFAULT);

        return $instance;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->password;
    }
}