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
     * @return ConfirmationToken
     */
    public static function fromString(string $token): self
    {
        $instance = new self();
        $instance->token = $token;

        return $instance;
    }

    public function toString(): string
    {
        return $this->token;
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
