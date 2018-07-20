<?php

namespace App\Domain\Category\Event;

use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;
use Assert\Assertion;
use Broadway\Serializer\Serializable;

class CategoryWasCreated implements Serializable
{
    public static function deserialize(array $data)
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'name');

        return new self(
            AggregatRootId::fromString($data['id']),
            Name::fromString($data['name'])
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->aggregatRootId->toString(),
            'name' => $this->name->toString()
        ];
    }

    /**
     * CategoryWasCreated constructor.
     * @param AggregatRootId $aggregatRootId
     * @param Name $name
     */
    public function __construct(AggregatRootId $aggregatRootId, Name $name)
    {
        $this->aggregatRootId = $aggregatRootId;
        $this->name = $name;
    }

    /**
     * @var AggregatRootId
     */
    private $aggregatRootId;

    /**
     * @var Name
     */
    private $name;

    /**
     * @return AggregatRootId
     */
    public function getAggregatRootId(): AggregatRootId
    {
        return $this->aggregatRootId;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
}