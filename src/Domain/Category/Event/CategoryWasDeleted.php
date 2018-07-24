<?php

namespace App\Domain\Category\Event;

use App\Domain\Common\ValueObject\AggregatRootId;
use Assert\Assertion;
use Broadway\Serializer\Serializable;

/**
 * Class CategoryWasDeleted
 * @package App\Domain\Category\Event
 */
class CategoryWasDeleted implements Serializable
{
    /**
     * @var AggregatRootId
     */
    private $id;

    /**
     * CategoryWasDeleted constructor.
     * @param AggregatRootId $id
     */
    public function __construct(AggregatRootId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AggregatRootId
     */
    public function getId(): AggregatRootId
    {
        return $this->id;
    }

    /**
     * @param array $data
     * @return CategoryWasDeleted
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');

        return new self(
            AggregatRootId::fromString($data['id'])
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id' => $this->id->toString()
        ];
    }
}