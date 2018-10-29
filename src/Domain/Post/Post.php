<?php

namespace App\Domain\Post;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\Post\ValueObject\Content;
use App\Domain\Post\ValueObject\Thumbnail;
use App\Domain\Post\ValueObject\Title;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

/**
 * Class Post
 * @package App\Domain\Post
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
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }

    /**
     * @param AggregateRootId $aggregateRootId
     * @param Title $title
     * @param Content $content
     * @param Thumbnail $thumbnail
     * @param string $type
     * @param string $user
     * @param string $category
     * @return Post
     */
    public static function create(AggregateRootId $aggregateRootId, Title $title, Content $content, Thumbnail $thumbnail, string $type, string $user, string $category): self
    {
        $post = new self();
        
        $post->apply(new CreatePostEvent(
            $aggregateRootId,
            $title,
            $content,
            $thumbnail,
            $type,
            $user,
            $category
        ));
        
        return $post;
    }

    /**
     * @param CreatePostEvent $event
     */
    public function applyCreatePostEvent(CreatePostEvent $event)
    {
        $this->id = $event->getId();
        $this->title = $event->getTitle();
        $this->content = $event->getContent();
        $this->type = $event->getType();
        $this->user = $event->getUser();
        $this->thumbnail = $event->getThumbnail();
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return Title
     */
    public function getTitle(): Title
    {
        return $this->title;
    }

    /**
     * @return Content
     */
    public function getContent(): Content
    {
        return $this->content;
    }

    /**
     * @return Thumbnail
     */
    public function getThumbnail(): Thumbnail
    {
        return $this->thumbnail;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }
}