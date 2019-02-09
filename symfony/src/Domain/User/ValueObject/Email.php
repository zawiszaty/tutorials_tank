<?php

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

/**
 * Class Email
 *
 * @package App\Domain\User\ValueObject
 */
class Email
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email
     *
     * @return Email
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $email): self
    {
        Assertion::email($email);

        $instance = new self();
        $instance->email = $email;

        return $instance;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->email;
    }
}
