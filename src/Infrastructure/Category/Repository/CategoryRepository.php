<?php

namespace App\Infrastructure\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\Repository\CategoryRepositoryInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Share\Broadway\EventSourcing\EventSourcingRepository;
use App\Infrastructure\Share\Event\Producer\EventToProjectionsProducer;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventStore\EventStore;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends EventSourcingRepository implements CategoryRepositoryInterface
{
    /**
     * @param AggregateRootId $id
     *
     * @return Category
     */
    public function get(AggregateRootId $id): Category
    {
        /** @var Category $category */
        $category = $this->load($id->toString());

        return $category;
    }

    /**
     * @param Category $category
     */
    public function store(Category $category): void
    {
        $this->save($category);
    }

    /**
     * CategoryRepository constructor.
     *
     * @param EventStore $eventStore
     * @param EventBus $eventBus
     * @param EventToProjectionsProducer $eventToProjectionsProducer
     * @param array $eventStreamDecorators
     */
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        EventToProjectionsProducer $eventToProjectionsProducer,
        array $eventStreamDecorators = []
    )
    {
        parent::__construct(
            $eventStore,
            $eventBus,
            Category::class,
            new PublicConstructorAggregateFactory(),
            $eventToProjectionsProducer,
            $eventStreamDecorators
        );
    }
}
