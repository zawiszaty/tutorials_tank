<?php

namespace App\Infrastructure\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\Repository\CategoryReposiotryInterface;
use App\Domain\Common\ValueObject\AggregatRootId;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;

class CategoryRepository extends EventSourcingRepository implements CategoryReposiotryInterface
{
    public function get(AggregatRootId $id): Category
    {
        /**
         * @var Category $category
         */
        $category = $this->load($id->toString());

        return $category;
    }

    public function store(Category $category): void
    {
       $this->save($category);
    }

    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = array()
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Category::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}