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
     * @param Serializable $event
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
     * @return CategoryView
     */
    public static function deserialize(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'];
        $instance->name = $data['name'];

        return $instance;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
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

    public function delete(): void
    {
    }

    /**
     * @param string $name
     */
    public function changeName(string $name)
    {
        $this->name = $name;
    }
}
