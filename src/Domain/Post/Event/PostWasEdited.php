<?php

namespace App\Domain\Post\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\ValueObject\Content;
use App\Domain\Post\ValueObject\Thumbnail;
use App\Domain\Post\ValueObject\Title;

/**
 * Class PostWasEdited.
 */
class PostWasEdited extends AbstractEvent
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
     * @var string
     */
    private $shortDescription;

    /**
     * CreatePostEvent constructor.
     *
     * @param AggregateRootId $id
     * @param Title           $title
     * @param Content         $content
     * @param Thumbnail       $thumbnail
     * @param string          $type
     * @param string          $user
     * @param null|string     $category
     * @param string          $shortDescription
     */
    public function __construct(AggregateRootId $id, Title $title, Content $content, Thumbnail $thumbnail, string $type, string $user, ?string $category, string $shortDescription)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->thumbnail = $thumbnail;
        $this->type = $type;
        $this->user = $user;
        $this->category = $category;
        $this->shortDescription = $shortDescription;
    }

    public static function deserialize(array $data)
    {
        $post = new self(
            AggregateRootId::fromString($data['id']),
            Title::fromString($data['title']),
            Content::fromString($data['content']),
            Thumbnail::fromString($data['thumbnail']),
            $data['type'],
            $data['user'],
            $data['category'],
            $data['shortDescription']
        );

        return $post;
    }

    public function serialize(): array
    {
        return [
            'id'               => $this->id->toString(),
            'title'            => $this->title->toString(),
            'content'          => $this->content->toString(),
            'thumbnail'        => $this->thumbnail->toString(),
            'type'             => $this->type,
            'user'             => $this->user,
            'category'         => $this->category,
            'shortDescription' => $this->shortDescription,
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

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }
}
