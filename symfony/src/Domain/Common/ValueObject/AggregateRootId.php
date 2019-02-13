<?php

namespace App\Domain\Common\ValueObject;

use Assert\Assertion;

/**
 * Class AggregateRootId.
 */
class AggregateRootId
{
    /**
     * @var string
     */
    private $aggregateRootId;

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return AggregateRootId
     */
    public static function fromString(string $id): self
    {
        Assertion::uuid($id);

        $aggregateRootId = new self();
        $aggregateRootId->aggregateRootId = $id;

        return $aggregateRootId;
    }

    public function toString(): string
    {
        return $this->aggregateRootId;
    }

    public function __toString(): string
    {
        return $this->aggregateRootId;
    }
}
