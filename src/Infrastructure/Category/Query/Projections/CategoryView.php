<?php

namespace App\Infrastructure\Category\Query\Projections;

use App\Domain\Category\Query\Projections\CategoryViewInterface;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Deleted;
use Broadway\Serializer\Serializable;

/**
 * Class CategoryView.
 */
class CategoryView implements CategoryViewInterface
{
    /**
     * @var AggregateRootId
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
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return CategoryView
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @param array $data
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return CategoryView
     */
    public static function deserialize(array $data): self
    {
        $instance = new self();
        $instance->id = AggregateRootId::fromString($data['id']);
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
            'id'      => $this->id->toString(),
            'name'    => $this->name->toString(),
            'deleted' => $this->deleted,
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function getDeleted(): bool
    {
        return $this->deleted;
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
