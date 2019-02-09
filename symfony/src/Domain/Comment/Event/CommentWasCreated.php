<?php

namespace App\Domain\Comment\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\ValueObject\Content;

/**
 * Class CommentWasCreated.
 */
class CommentWasCreated extends AbstractEvent
{
    /**
     * @var AggregateRootId
     */
    protected $id;

    /**
     * @var Content
     */
    private $content;

    /**
     * @var string
     */
    private $parrentComment;

    /**
     * @var string
     */
    private $post;

    /**
     * @var string
     */
    private $user;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * CommentWasCreated constructor.
     *
     * @param AggregateRootId $id
     * @param Content         $content
     * @param string          $parrentComment
     * @param string          $post
     * @param string          $user
     */
    public function __construct(AggregateRootId $id, Content $content, ?string $parrentComment, string $post, string $user, \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->content = $content;
        $this->parrentComment = $parrentComment;
        $this->post = $post;
        $this->user = $user;
        $this->createdAt = $createdAt;
    }

    /**
     * @param array $data
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     *
     * @return CommentWasCreated|mixed
     */
    public static function deserialize(array $data)
    {
        $instance = new self(
            AggregateRootId::fromString($data['id']),
            Content::fromString($data['content']),
            $data['parrentComment'],
            $data['post'],
            $data['user'],
            new \DateTime($data['createdAt']['date'])
        );

        return $instance;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id'             => $this->getId()->toString(),
            'content'        => $this->getContent()->toString(),
            'parrentComment' => $this->getParrentComment(),
            'post'           => $this->getPost(),
            'user'           => $this->getUser(),
            'createdAt'      => $this->getCreatedAt(),
        ];
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return Content
     */
    public function getContent(): Content
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getParrentComment(): ?string
    {
        return $this->parrentComment;
    }

    /**
     * @return string
     */
    public function getPost(): string
    {
        return $this->post;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
