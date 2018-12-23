<?php

namespace App\Infrastructure\User\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use App\Infrastructure\Share\Broadway\EventSourcing\EventSourcingRepository;
use App\Infrastructure\Share\Event\Producer\EventToProjectionsProducer;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventStore\EventStore;

class UserRepository extends EventSourcingRepository implements UserRepositoryInterface
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
     * @param EventStore                 $eventStore
     * @param EventBus                   $eventBus
     * @param EventToProjectionsProducer $eventToProjectionsProducer
     * @param array                      $eventStreamDecorators
     */
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        EventToProjectionsProducer $eventToProjectionsProducer,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            User::class,
            new PublicConstructorAggregateFactory(),
            $eventToProjectionsProducer,
            $eventStreamDecorators
        );
    }
}
