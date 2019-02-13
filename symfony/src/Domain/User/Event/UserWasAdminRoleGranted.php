<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class UserWasAdminRoleGranted.
 */
class UserWasAdminRoleGranted extends AbstractEvent
{
    /**
     * UserWasAdminRoleGranted constructor.
     */
    public function __construct(AggregateRootId $id)
    {
        $this->id = $id;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        $instance = new self(AggregateRootId::fromString($data['id']));

        return $instance;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->getId()->toString(),
        ];
    }
}
