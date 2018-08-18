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
     *
     * @param AggregateRootId $id
     * @param Name            $name
     */
    public function __construct(AggregateRootId $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param array $data
     *
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

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id'      => $this->id->toString(),
            'name'    => $this->name->toString(),
            'deleted' => false,
        ];
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
}
