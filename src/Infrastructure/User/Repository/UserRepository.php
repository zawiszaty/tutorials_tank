<?php

namespace App\Infrastructure\User\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\User;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;

class UserRepository extends EventSourcingRepository
{
    /**
     * @param AggregateRootId $id
     *
     * @return User
     */
    public function get(AggregateRootId $id): User
    {
       /** @var User $user */
        $user = $this->load($id->toString());

        return $user;
    }

    /**
     * @param User $user
     */
    public function store(User $user): void
    {
        $this->save($user);
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
            User::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}