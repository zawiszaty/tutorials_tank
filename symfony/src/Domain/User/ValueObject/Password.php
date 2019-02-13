<?php

namespace App\Domain\User\ValueObject;

/**
 * Class Password.
 */
class Password
{
    /**
     * @var null|string
     */
    private $password;

    /**
     * @return password
     */
    public static function fromString(string $password): self
    {
        $instance = new self();
        $instance->password = $password;

        return $instance;
    }

    /**
     * @return Password
     */
    public static function formHash(string $password): self
    {
        $instance = new self();
        $instance->password = $password;

        return $instance;
    }

    public function toString(): string
    {
        return $this->password;
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
