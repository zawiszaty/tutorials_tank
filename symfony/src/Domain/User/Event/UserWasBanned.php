<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class UserWasConfirmed.
 */
class UserWasBanned extends AbstractEvent
{
    /**
     * @var bool
     */
    private $banned;

    /**
     * UserWasConfirmed constructor.
     */
    public function __construct(AggregateRootId $aggregateRootId, bool $banned)
    {
        $this->id = $aggregateRootId;
        $this->banned = $banned;
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return UserWasConfirmed|mixed
     */
    public static function deserialize(array $data)
    {
        $event = new self(
            AggregateRootId::fromString($data['id']),
            $data['banned']
        );

        return $event;
    }

    public function serialize(): array
    {
        return [
            'id'     => $this->id->toString(),
            'banned' => $this->banned,
        ];
    }

    public function isBanned(): bool
    {
        return $this->banned;
    }
}
