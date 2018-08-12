<?php

namespace App\Domain\Category;

use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\Event\CategoryWasDeleted;
use App\Domain\Category\Event\NameWasChanged;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;
use App\Domain\Common\ValueObject\Deleted;
use Assert\Assertion;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use function Symfony\Component\DependencyInjection\Tests\Fixtures\factoryFunction;

/**
 * Class Category
 * @package App\Domain\Category
 */
class Category extends EventSourcedAggregateRoot
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
     * @param array $params
     * @return Category
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(array $params): self
    {
        $self = new self();
        $self->id = AggregatRootId::fromString($params['id']);
        $self->name = Name::fromString($params['name']);
        $self->deleted = Deleted::fromString($params['deleted']);

        return $self;
    }

    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }

    /**
     * @param AggregatRootId $id
     * @param Name $name
     * @return Category
     * @throws \Assert\AssertionFailedException
     */
    public static function create(AggregatRootId $id, Name $name): self
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
     */
    public function changeName(Name $name)
    {
        $this->apply(new NameWasChanged($this->id, $name));
    }

    /**
     * @param NameWasChanged $event
     */
    public function applyNameWasChanged(NameWasChanged $event): void
    {
//        Assertion::notEq('test', '2', 'New Name should be different');

        $this->name = $event->getName();
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function delete()
    {
//        Assertion::notEq($this->deleted, 1,'This Category is delete');

        $this->apply(new CategoryWasDeleted($this->id));
    }

    public function applyCategoryWasDeleted()
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