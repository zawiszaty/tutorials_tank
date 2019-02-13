<?php

namespace App\Infrastructure\User\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;

/**
 * Class UserRepository.
 */
class UserRepository extends EventSourcingRepository implements UserRepositoryInterface
{
    public function get(AggregateRootId $id): User
    {
        /** @var User $user */
        $user = $this->load($id->toString());

        return $user;
    }

    public function store(User $user): void
    {
        $this->save($user);
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
            User::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}
