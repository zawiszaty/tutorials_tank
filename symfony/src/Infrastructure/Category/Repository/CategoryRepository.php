<?php

namespace App\Infrastructure\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\Repository\CategoryRepositoryInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends EventSourcingRepository implements CategoryRepositoryInterface
{
    public function get(AggregateRootId $id): Category
    {
        /** @var Category $category */
        $category = $this->load($id->toString());

        return $category;
    }

    public function store(Category $category): void
    {
        $this->save($category);
    }

    /**
     * CategoryRepository constructor.
     */
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
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
