<?php

namespace App\Domain\Comment\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class CommentWasDeletedEvent.
 */
class CommentWasDeletedEvent extends AbstractEvent
{
    /**
     * @var string
     */
    private $user;

    /**
     * CommentWasDeletedEvent constructor.
     */
    public function __construct(AggregateRootId $id, string $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return CommentWasDeletedEvent|mixed
     */
    public static function deserialize(array $data)
    {
        $comment = new self(
            AggregateRootId::fromString($data['id']),
            $data['user']
        );

        return $comment;
    }

    public function serialize(): array
    {
        return [
            'id'   => $this->id->toString(),
            'user' => $this->user,
        ];
    }
}
