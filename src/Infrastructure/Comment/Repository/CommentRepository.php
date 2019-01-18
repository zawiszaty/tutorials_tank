<?php

namespace App\Infrastructure\Comment\Repository;

use App\Domain\Comment\Comment;
use App\Domain\Comment\Repository\CommentRepositoryInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;

/**
 * Class CommentRepository
 *
 * @package App\Infrastructure\Comment\Repository
 */
class CommentRepository extends EventSourcingRepository implements CommentRepositoryInterface
{
    public function get(AggregateRootId $id): Comment
    {
        /** @var Comment $category */
        $category = $this->load($id->toString());

        return $category;
    }

    public function store(Comment $category): void
    {
        $this->save($category);
    }

    /**
     * CommentRepository constructor.
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
            Comment::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}
