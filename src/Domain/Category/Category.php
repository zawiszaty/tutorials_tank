<?php

namespace App\Domain\Category;

use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\Event\CategoryWasDeleted;
use App\Domain\Category\Event\NameWasChanged;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Deleted;
use Assert\Assertion;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

/**
 * Class Category.
 */
class Category extends EventSourcedAggregateRoot
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
     * @param array $params
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return Category
     */
    public static function fromString(array $params): self
    {
        $self = new self();
        $self->id = AggregateRootId::fromString($params['id']);
        $self->name = Name::fromString($params['name']);
        $self->deleted = Deleted::fromString($params['deleted']);

        return $self;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
          "id" => $this->id->toString(),
          "name" => $this->name->toString(),
          "deleted" => $this->deleted->toBool()
        ];
    }

    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }

    /**
     * @param AggregateRootId $id
     * @param Name            $name
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return Category
     */
    public static function create(AggregateRootId $id, Name $name): self
    {
        $category = new self();
        $category->apply(new CategoryWasCreated($id, $name, Deleted::fromString(false)));

        return $category;
    }

    /**
     * @param CategoryWasCreated $event
     */
    protected function applyCategoryWasCreated(CategoryWasCreated $event): void
    {
        $this->id = $event->getId();
        $this->name = $event->getName();
        $this->deleted = $event->getDeleted();
    }

    /**
     * @param Name $name
     * @throws \Assert\AssertionFailedException
     */
    public function changeName(Name $name): void
    {
        Assertion::notSame($this->name->toString(), $name->toString(), 'New Name should be different');
        $this->apply(new NameWasChanged($this->id, $name));
    }

    /**
     * @param NameWasChanged $event
     */
    public function applyNameWasChanged(NameWasChanged $event): void
    {
        $this->name = $event->getName();
    }

    /**
     *
     */
    public function delete(): void
    {
        $this->apply(new CategoryWasDeleted($this->id));
    }

    /**
     *
     */
    public function applyCategoryWasDeleted(): void
    {
        $this->deleted = 1;
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
     * @return string
     */
    public function getDeleted(): string
    {
        return $this->deleted->toString();
    }
}
