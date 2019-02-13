<?php

namespace App\Domain\Post;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\Post\Event\PostEventDelete;
use App\Domain\Post\Event\PostWasEdited;
use App\Domain\Post\ValueObject\Content;
use App\Domain\Post\ValueObject\Thumbnail;
use App\Domain\Post\ValueObject\Title;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * Class Post.
 */
class Post extends EventSourcedAggregateRoot
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var Content
     */
    private $content;

    /**
     * @var Thumbnail
     */
    private $thumbnail;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $shortDescription;

    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }

    /**
     * @return Post
     */
    public static function create(AggregateRootId $aggregateRootId, Title $title, Content $content, Thumbnail $thumbnail, string $type, string $user, string $category, string $shortDescription): self
    {
        $post = new self();

        $post->apply(new CreatePostEvent(
            $aggregateRootId,
            $title,
            $content,
            $thumbnail,
            $type,
            $user,
            $category,
            $shortDescription
        ));

        return $post;
    }

    public function applyCreatePostEvent(CreatePostEvent $event)
    {
        $this->id = $event->getId();
        $this->title = $event->getTitle();
        $this->content = $event->getContent();
        $this->type = $event->getType();
        $this->user = $event->getUser();
        $this->thumbnail = $event->getThumbnail();
        $this->shortDescription = $event->getShortDescription();
        $this->category = $event->getCategory();
    }

    /**
     * @param string $category
     *
     * @return Post
     */
    public function edit(Title $title, Content $content, Thumbnail $thumbnail, string $type, string $user, ?string $category, string $shortDescription)
    {
        $this->apply(new PostWasEdited(
            $this->getId(),
            $title,
            $content,
            $thumbnail,
            $type,
            $user,
            $category,
            $shortDescription
        ));

        return $this;
    }

    public function delete(string $user)
    {
        if ($user != $this->getUser()) {
            throw  new AccessDeniedException();
        }

        $this->apply(new PostEventDelete($this->id, $user));
    }

    public function applyPostEventDelete(PostEventDelete $eventDelete)
    {
    }

    public function applyPostWasEdited(PostWasEdited $event)
    {
        $this->title = $event->getTitle();
        $this->content = $event->getContent();
        $this->type = $event->getType();
        $this->user = $event->getUser();
        $this->thumbnail = $event->getThumbnail();
        $this->shortDescription = $event->getShortDescription();
        $this->category = $event->getCategory();
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getThumbnail(): Thumbnail
    {
        return $this->thumbnail;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }
}
