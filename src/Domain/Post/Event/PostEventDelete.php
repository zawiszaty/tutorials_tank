<?php

namespace App\Domain\Post\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;

class PostEventDelete extends AbstractEvent
{
    /**
     * @var string
     */
    private $user;

    public function __construct(AggregateRootId $id, string $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * @param array $data
     * @return mixed The object instance
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data)
    {
        $self = new self(
            AggregateRootId::fromString($data['id']),
            $data['user']
        );

        return $self;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            "id" => $this->id->toString(),
            "user" => $this->user
        ];
    }
}