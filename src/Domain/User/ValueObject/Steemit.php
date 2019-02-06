<?php

namespace App\Domain\User\ValueObject;

/**
 * Class Steemit
 *
 * @package App\Domain\User\ValueObject
 */
class Steemit
{
    /**
     * @var null|string
     */
    private $steemit;

    /**
     * @param string $steemit
     *
     * @return steemit
     */
    public static function fromString(?string $steemit): self
    {
        $instance = new self();
        $instance->steemit = $steemit;

        return $instance;
    }

    /**
     * @return string
     */
    public function toString(): ?string
    {
        return $this->steemit;
    }

    /**
     * @return string
     */
    public function __toString(): ?string
    {
        return $this->steemit;
    }
}
