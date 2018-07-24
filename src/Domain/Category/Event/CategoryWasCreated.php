<?php

namespace App\Domain\Category\Event;

use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;
use App\Domain\Common\ValueObject\Deleted;
use Assert\Assertion;
use Broadway\Serializer\Serializable;

/**
 * Class CategoryWasCreated
 * @package App\Domain\Category\Event
 */
class CategoryWasCreated implements Serializable
{
    /**
     * @param array $data
     * @return CategoryWasCreated
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'name');
        Assertion::keyExists($data, 'deleted');

        return new self(
            AggregatRootId::fromString($data['id']),
            Name::fromString($data['name']),
            Deleted::fromString($data['deleted'])
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id' => $this->aggregatRootId->toString(),
            'name' => $this->name->toString(),
            'deleted' => $this->deleted->toBool(),
        ];
    }

    /**
     * CategoryWasCreated constructor.
     * @param AggregatRootId $aggregatRootId
     * @param Name $name
     * @param Deleted $deleted
     */
    public function __construct(AggregatRootId $aggregatRootId, Name $name, Deleted $deleted)
    {
        $this->aggregatRootId = $aggregatRootId;
        $this->name = $name;
        $this->deleted = $deleted;
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
     * @var Deleted
     */
    private $deleted;

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

    /**
     * @return Deleted
     */
    public function getDeleted(): Deleted
    {
        return $this->deleted;
    }
}