<?php

namespace App\Infrastructure\Post\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Post;
use App\Infrastructure\Share\Event\Producer\EventToProjectionsProducer;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;

/**
 * Class PostRepository.
 */
class PostRepository extends EventSourcingRepository
{
    /**
     * @var EventToProjectionsProducer
     */
    private $eventToProjectionsProducer;

    /**
     * @param AggregateRootId $id
     *
     * @return Post
     */
    public function get(AggregateRootId $id): Post
    {
        /** @var Post $postType */
        $postType = $this->load($id->toString());

        return $postType;
    }

    /**
     * @param Post $postType
     */
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
