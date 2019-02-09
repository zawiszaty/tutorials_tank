<?php

namespace App\Domain\User\ValueObject;

/**
 * Class Avatar
 *
 * @package App\Domain\User\ValueObject
 */
class Avatar
{
    /**
     * @var null|string
     */
    private $avatar;

    /**
     * @param string $avatar
     *
     * @return avatar
     */
    public static function fromString(?string $avatar): self
    {
        $instance = new self();
        $instance->avatar = $avatar;

        return $instance;
    }

    /**
     * @return string
     */
    public function toString(): ?string
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function __toString(): ?string
    {
        return $this->avatar;
    }
}
