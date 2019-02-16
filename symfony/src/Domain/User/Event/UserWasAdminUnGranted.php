<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class UserWasAdminUnGranted.
 */
class UserWasAdminUnGranted extends AbstractEvent
{
    /**
     * UserWasConfirmed constructor.
     */
    public function __construct(AggregateRootId $aggregateRootId)
    {
        $this->id = $aggregateRootId;
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return UserWasConfirmed|mixed
     */
    public static function deserialize(array $data)
    {
        $event = new self(
            AggregateRootId::fromString($data['id'])
        );

        return $event;
    }

    public function serialize(): array
    {
        return [
            'id'     => $this->id->toString(),
        ];
    }
}
