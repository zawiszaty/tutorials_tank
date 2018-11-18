<?php

namespace App\Infrastructure\Post\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Post;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;

class PostRepository extends EventSourcingRepository
{
    public function get(AggregateRootId $id): Post
    {
        /** @var Post $postType */
        $postType = $this->load($id->toString());

        return $postType;
    }

    public function store(Post $postType): void
    {
        $this->save($postType);
    }

    /**
     * CategoryRepository constructor.
     *
     * @param EventStore $eventStore
     * @param EventBus   $eventBus
     * @param array      $eventStreamDecorators
     */
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Post::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}
