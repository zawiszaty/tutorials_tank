<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

/**
 * Class ConfirmationToken.
 */
class ConfirmationToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     *
     * @return ConfirmationToken
     */
    public static function fromString(string $token): self
    {
        $instance = new self();
        $instance->token = $token;

        return $instance;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->token;
    }
}
