<?php

namespace App\Domain\Category\Event;

use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;
use App\Domain\Common\ValueObject\Deleted;
use Assert\Assertion;
use Broadway\Serializer\Serializable;

/**
 * Class NameWasChanged
 * @package App\Domain\Category\Event
 */
class NameWasChanged implements Serializable
{
    /**
     * @var AggregatRootId
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    /**
     * NameWasChanged constructor.
     * @param AggregatRootId $id
     * @param Name $name
     */
    public function __construct(AggregatRootId $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param array $data
     * @return NameWasChanged
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'name');

        return new self(
            AggregatRootId::fromString($data['id']),
            Name::fromString($data['name'])
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'name' => $this->name->toString()
        ];
    }

    /**
     * @return AggregatRootId
     */
    public function getId(): AggregatRootId
    {
        return $this->id;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
}