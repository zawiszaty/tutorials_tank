<?php

namespace App\Infrastructure\Post\Query\Projections;

use App\Domain\User\Query\Projections\UserViewInterface;
use Broadway\Serializer\Serializable;

class PostView implements UserViewInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string|null
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
    private $slug;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param Serializable $event
     *
     * @return PostView
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @param array $data
     * @return PostView|mixed
     */
    public static function deserialize(array $data)
    {
        $userView = new self();
        $userView->id = $data['id'];
        $userView->title = $data['title'];
        $userView->content = $data['content'];
        $userView->thumbnail = $data['thumbnail'];
        $userView->type = $data['type'];
        $userView->user = $data['user'];
        $userView->slug = $data['slug'];
        $userView->createdAt = $data['createdAt'];
        $userView->category = $data['category'];

        return $userView;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail,
            'type' => $this->type,
            'user' => $this->user,
            'slug' => $this->slug,
            'createdAt' => $this->createdAt,
            'category' => $this->category,
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getThumbnail(): ?string
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
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }
}