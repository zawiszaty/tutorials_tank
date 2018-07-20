<?php

namespace App\Infrastructure\Category\Query\Projections;

use App\Domain\Category\Query\Projections\CategoryViewInterface;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;
use Broadway\Serializer\Serializable;

class CategoryView implements CategoryViewInterface
{
    /**
     * @var AggregatRootId
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @param array $data
     * @return CategoryView
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        $instance = new self();
        $instance->id = AggregatRootId::fromString($data['id']);
        $instance->name = Name::fromString($data['name']);

        return $instance;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'name' => $this->name->toString()
        ];
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }
}