<?php

namespace App\Domain\Post\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class PostEventDelete.
 */
class PostEventDelete extends AbstractEvent
{
    /**
     * @var string
     */
    private $user;

    /**
     * PostEventDelete constructor.
     */
    public function __construct(AggregateRootId $id, string $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        $self = new self(
            AggregateRootId::fromString($data['id']),
            $data['user']
        );

        return $self;
    }

    public function serialize(): array
    {
        return [
            'id'   => $this->id->toString(),
            'user' => $this->user,
        ];
    }
}
