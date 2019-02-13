<?php

namespace App\Domain\Category\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use Assert\Assertion;

/**
 * Class CategoryWasDeleted.
 */
class CategoryWasDeleted extends AbstractEvent
{
    /**
     * CategoryWasDeleted constructor.
     */
    public function __construct(AggregateRootId $id)
    {
        $this->id = $id;
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return CategoryWasDeleted
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');

        return new self(
            AggregateRootId::fromString($data['id'])
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
        ];
    }
}
