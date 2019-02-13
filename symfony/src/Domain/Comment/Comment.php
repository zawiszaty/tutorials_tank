<?php

namespace App\Domain\Comment;

use App\Domain\Comment\Event\CommentWasCreated;
use App\Domain\Comment\Event\CommentWasDeletedEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\ValueObject\Content;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Symfony\Component\Finder\Exception\AccessDeniedException;

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

    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getParrentComment(): ?string
    {
        return $this->parrentComment;
    }

    public function getPost(): string
    {
        return $this->post;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $parrentComment
     *
     * @throws \Exception
     *
     * @return Comment
     */
    public static function create(
        AggregateRootId $aggregateRootId,
        Content $content,
        ?string $parrentComment,
        string $post,
        string $user
    ) {
        $comment = new self();

        if ($parrentComment) {
            $comment->apply(new CommentWasCreated(
                $aggregateRootId,
                $content,
                $parrentComment,
                $post,
                $user,
                new \DateTime()
            ));
        } else {
            $comment->apply(new CommentWasCreated(
                $aggregateRootId,
                $content,
                null,
                $post,
                $user,
                new \DateTime()
            ));
        }

        return $comment;
    }

    public function applyCommentWasCreated(CommentWasCreated $event)
    {
        $this->id = $event->getId();
        $this->content = $event->getContent();
        $this->parrentComment = $event->getParrentComment();
        $this->post = $event->getPost();
        $this->user = $event->getUser();
    }

    public function delete(string $user): void
    {
        if ($this->user !== $user) {
            throw new AccessDeniedException();
        }
        $this->apply(new CommentWasDeletedEvent($this->id, $user));
    }

    public function applyCommentWasDeletedEvent(CommentWasDeletedEvent $commentWasDeletedEvent): void
    {
    }
}
