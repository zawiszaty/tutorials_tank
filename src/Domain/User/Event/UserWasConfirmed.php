<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class UserWasConfirmed.
 */
class UserWasConfirmed extends AbstractEvent
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * UserWasConfirmed constructor.
     *
     * @param AggregateRootId $aggregateRootId
     * @param bool            $enabled
     */
    public function __construct(AggregateRootId $aggregateRootId, bool $enabled)
    {
        $this->id = $aggregateRootId;
        $this->enabled = $enabled;
    }

    /**
     * @param array $data
     *
     * @return UserWasConfirmed|mixed
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data)
    {
        $event = new self(
            AggregateRootId::fromString($data['id']),
            $data['enabled']
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
            'enabled' => $this->enabled,
        ];
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
