<?php

namespace App\Domain\Common\ValueObject;


use Assert\Assertion;

class AggregatRootId
{
    /**
     * @var string
     */
    private $aggregatRootId;

    /**
     * @param string $id
     * @return AggregatRootId
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $id): self
    {
        Assertion::uuid($id);

        $aggregatRootId = new self();
        $aggregatRootId->aggregatRootId = $id;

        return $aggregatRootId;
    }

    public function toString(): string
    {
        return $this->aggregatRootId;
    }

    public function __toString(): string
    {
        return $this->aggregatRootId;
    }
}