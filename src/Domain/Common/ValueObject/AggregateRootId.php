<?php

namespace App\Domain\Common\ValueObject;

use Assert\Assertion;

/**
 * Class AggregateRootId
 * @package App\Domain\Common\ValueObject
 */
class AggregateRootId
{
    /**
     * @var string
     */
    private $aggregateRootId;

    /**
     * @param string $id
     * @return AggregateRootId
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $id): self
    {
        Assertion::uuid($id);

        $aggregateRootId = new self();
        $aggregateRootId->aggregateRootId = $id;

        return $aggregateRootId;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->aggregateRootId;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->aggregateRootId;
    }
}