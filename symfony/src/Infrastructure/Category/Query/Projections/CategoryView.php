<?php

namespace App\Infrastructure\Category\Query\Projections;

use App\Domain\Category\Query\Projections\CategoryViewInterface;
use Broadway\Serializer\Serializable;

/**
 * Class CategoryView.
 */
class CategoryView implements CategoryViewInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @return CategoryView
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @return CategoryView
     */
    public static function deserialize(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'];
        $instance->name = $data['name'];

        return $instance;
    }

    public function serialize(): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function delete(): void
    {
    }

    public function changeName(string $name)
    {
        $this->name = $name;
    }
}
