<?php

namespace App\Domain\Category;

use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\Event\NameWasChanged;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;
use Assert\Assertion;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

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
     */
    public static function create(AggregatRootId $id, Name $name): self
    {
        $category = new self();
        $category->apply(new CategoryWasCreated($id, $name));

        return $category;
    }

    /**
     * @param CategoryWasCreated $event
     */
    protected function applyCategoryWasCreated(CategoryWasCreated $event): void
    {
        $this->id = $event->getAggregatRootId();
        $this->name = $event->getName();
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

}