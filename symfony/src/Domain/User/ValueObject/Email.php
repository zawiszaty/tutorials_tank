<?php

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

/**
 * Class Email.
 */
class Email
{
    /**
     * @var string
     */
    private $email;

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return Email
     */
    public static function fromString(string $email): self
    {
        Assertion::email($email);

        $instance = new self();
        $instance->email = $email;

        return $instance;
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
