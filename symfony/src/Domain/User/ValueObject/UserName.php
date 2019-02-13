<?php

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

/**
 * Class UserName.
 */
class UserName
{
    /**
     * @var string
     */
    private $userName;

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return UserName
     */
    public static function fromString(string $userName): self
    {
        Assertion::string($userName);

        $instance = new self();
        $instance->userName = $userName;

        return $instance;
    }

    public function toString(): string
    {
        return $this->userName;
    }

    public function __toString(): string
    {
        return $this->userName;
    }
}
