<?php

namespace App\Domain\Category\ValueObject;

use Assert\Assertion;

/**
 * Class Name
 * @package App\Domain\Category\ValueObject
 */
class Name
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @return Name
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $name): self
    {
        Assertion::string($name);

        $instance = new self();
        $instance->name = $name;

        return $instance;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Name constructor.
     */
    private function __construct()
    {
    }
}