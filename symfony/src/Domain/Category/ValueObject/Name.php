<?php

namespace App\Domain\Category\ValueObject;

use Assert\Assertion;

/**
 * Class Name.
 */
class Name
{
    /**
     * @var string
     */
    private $name;

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return Name
     */
    public static function fromString(string $name): self
    {
        Assertion::string($name);

        $instance = new self();
        $instance->name = $name;

        return $instance;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
