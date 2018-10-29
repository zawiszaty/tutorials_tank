<?php

namespace App\Domain\Post\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\ValueObject\Content;
use App\Domain\Post\ValueObject\Thumbnail;
use App\Domain\Post\ValueObject\Title;

/**
 * Class CreatePostEvent
 * @package App\Domain\Post\Event
 */
class CreatePostEvent extends AbstractEvent
{
    /**
     * @var AggregateRootId
     */
    protected $id;

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
     * @var string|null
     */
    private $category;

    /**
     * CreatePostEvent constructor.
     * @param AggregateRootId $id
     * @param Title $title
     * @param Content $content
     * @param Thumbnail $thumbnail
     * @param string $type
     * @param string $user
     */
    public function __construct(AggregateRootId $id, Title $title, Content $content, Thumbnail $thumbnail, string $type, string $user, ?string $category)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->thumbnail = $thumbnail;
        $this->type = $type;
        $this->user = $user;
        $this->category = $category;
    }

    public static function deserialize(array $data)
    {
        $post = new self(
            $data['id'],
            $data['title'],
            $data['content'],
            $data['thumbnail'],
            $data['type'],
            $data['user'],
            $data['category']
        );

        return $post;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'title' => $this->title->toString(),
            'content' => $this->content->toString(),
            'thumbnail' => $this->thumbnail->toString(),
            'type' => $this->type,
            'user' => $this->user,
            'category' => $this->category,
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
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }
}