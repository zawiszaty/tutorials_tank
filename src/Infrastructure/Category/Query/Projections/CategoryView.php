<?php

namespace App\Infrastructure\Category\Query\Projections;

use App\Domain\Category\Query\Projections\CategoryViewInterface;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;
use App\Domain\Common\ValueObject\Deleted;
use Broadway\Serializer\Serializable;

/**
 * Class CategoryView
 * @package App\Infrastructure\Category\Query\Projections
 */
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

    /**
     * @var Deleted
     */
    private $deleted;

    /**
     * @param Serializable $event
     * @return CategoryView
     * @throws \Assert\AssertionFailedException
     */
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
        $instance->deleted = Deleted::fromString($data['deleted'])->toBool();

        return $instance;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'name' => $this->name->toString(),
            'deleted' => $this->deleted->toBool()
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id->toString();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name->toString();
    }

    /**
     * @return boolean
     */
    public function getDeleted(): bool
    {
        return $this->deleted->toBool();
    }

    /**
     * @param Name $name
     */
    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function delete(): void
    {
        $this->deleted = 1;
    }
}