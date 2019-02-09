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
     * @param string $userName
     *
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
