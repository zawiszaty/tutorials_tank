<?php

namespace App\Domain\Comment\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class CommentWasDeletedEvent
 * @package App\Domain\Comment\Event
 */
class CommentWasDeletedEvent extends AbstractEvent
{
    /**
     * @var string
     */
    private $user;

    /**
     * CommentWasDeletedEvent constructor.
     * @param AggregateRootId $id
     * @param string $user
     */
    public function __construct(AggregateRootId $id, string $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param array $data
     * @return CommentWasDeletedEvent|mixed
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data)
    {
        $comment = new self(
            AggregateRootId::fromString($data['id']),
            $data['user']
        );

        return $comment;
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