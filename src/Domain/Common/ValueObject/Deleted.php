<?php

namespace App\Domain\Common\ValueObject;

use Assert\Assertion;

/**
 * Class Deleted
 * @package App\Domain\Common\ValueObject
 */
class Deleted
{
    /**
     * @var bool
     */
    private $deleted;

    /**
     * @param bool $deleted
     * @return Deleted
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(bool $deleted): self
    {
        Assertion::boolean($deleted);

        $instance = new self();
        $instance->deleted = $deleted;

        return $instance;
    }

    /**
     * @return bool
     */
    public function toBool(): bool
    {
        return $this->deleted;
    }
}