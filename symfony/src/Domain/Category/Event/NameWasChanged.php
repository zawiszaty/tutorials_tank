<?php

namespace App\Domain\Category\Event;

use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use Assert\Assertion;

/**
 * Class NameWasChanged.
 */
class NameWasChanged extends AbstractEvent
{
    /**
     * @var Name
     */
    private $name;

    /**
     * NameWasChanged constructor.
     */
    public function __construct(AggregateRootId $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return NameWasChanged
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'name');

        return new self(
            AggregateRootId::fromString($data['id']),
            Name::fromString($data['name'])
        );
    }

    public function serialize(): array
    {
        return [
            'id'   => $this->id->toString(),
            'name' => $this->name->toString(),
        ];
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
