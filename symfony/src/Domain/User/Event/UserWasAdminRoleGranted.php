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
     *
     * @param AggregateRootId $id
     */
    public function __construct(AggregateRootId $id)
    {
        $this->id = $id;
    }

    /**
     * @param array $data
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return self
     */
    public static function deserialize(array $data): self
    {
        $instance = new self(AggregateRootId::fromString($data['id']));

        return $instance;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id' => $this->getId()->toString(),
        ];
    }
}
