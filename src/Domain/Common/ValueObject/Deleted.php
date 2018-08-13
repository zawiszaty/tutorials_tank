<?php

namespace App\Domain\Common\ValueObject;

use Assert\Assertion;

/**
 * Class Deleted.
 */
class Deleted
{
    /**
     * @var bool
     */
    private $deleted;

    /**
     * @param bool $deleted
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return Deleted
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
