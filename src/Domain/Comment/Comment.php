<?php

namespace App\Domain\Comment;

use App\Domain\Comment\Event\CommentWasCreated;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\ValueObject\Content;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

/**
 * Class Comment.
 */
class Comment extends EventSourcedAggregateRoot
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
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->id->toString();
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
    public function getParrentComment(): string
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
     * @param AggregateRootId $aggregateRootId
     * @param Content         $content
     * @param string          $parrentComment
     * @param string          $post
     * @param string          $user
     *
     * @return Comment
     *
     * @throws \Exception
     */
    public static function create(
        AggregateRootId $aggregateRootId,
        Content $content,
        ?string $parrentComment,
        string $post,
        string $user
    ) {
        $comment = new self();
        $comment->apply(new CommentWasCreated(
            $aggregateRootId,
            $content,
            $parrentComment,
            $post,
            $user,
            new \DateTime()
        ));

        return $comment;
    }

    /**
     * @param CommentWasCreated $event
     */
    public function applyCommentWasCreated(CommentWasCreated $event)
    {
        $this->id = $event->getId();
        $this->content = $event->getContent();
        $this->parrentComment = $event->getParrentComment();
        $this->post = $event->getPost();
        $this->user = $event->getUser();
    }
}
