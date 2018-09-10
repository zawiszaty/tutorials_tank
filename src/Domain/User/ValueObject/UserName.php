<?php

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

class UserName
{
    /**
     * @var string
     */
    private $userName;

    /**
     * @param string $userName
     *
     * @return UserName
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $userName): self
    {
        Assertion::string($userName);

        $instance = new self();
        $instance->userName = $userName;

        return $instance;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->userName;
    }
}
