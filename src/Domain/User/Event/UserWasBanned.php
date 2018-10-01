<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class UserWasConfirmed
 * @package App\Domain\User\Event
 */
class UserWasBanned extends AbstractEvent
{
    /**
     * @var bool
     */
    private $banned;

    /**
     * UserWasConfirmed constructor.
     * @param AggregateRootId $aggregateRootId
     * @param bool $banned
     */
    public function __construct(AggregateRootId $aggregateRootId, bool $banned)
    {
        $this->id = $aggregateRootId;
        $this->banned = $banned;
    }

    /**
     * @param array $data
     * @return UserWasConfirmed|mixed
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data)
    {
        $event = new self(
            AggregateRootId::fromString($data['id']),
            $data['banned']
        );

        return $event;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'banned' => $this->banned
        ];
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->banned;
    }
}