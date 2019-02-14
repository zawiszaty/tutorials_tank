<?php

namespace App\Infrastructure\Post\Query\Projections;

use App\Domain\User\Query\Projections\UserViewInterface;
use App\Infrastructure\User\Query\Projections\UserView;
use Broadway\Serializer\Serializable;

/**
 * Class PostView.
 */
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
     * @var string
     */
    private $shortDescription;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @var array
     */
    private $comment;

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return PostView
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @throws \Exception
     *
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
        $userView->shortDescription = $data['shortDescription'];

        return $userView;
    }

    public function serialize(): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'content'          => $this->content,
            'thumbnail'        => $this->thumbnail,
            'type'             => $this->type,
            'user'             => $this->user->getId(),
            'slug'             => $this->slug,
            'createdAt'        => $this->createdAt->getTimestamp(),
            'category'         => $this->category->getId(),
            'shortDescription' => $this->shortDescription,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

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

    public function getType(): string
    {
        return $this->type;
    }

    public function getUser(): string
    {
        return $this->user->getId();
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getCategory(): ?string
    {
        if ($this->category) {
            return $this->category->getId();
        }

        return null;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function edit(array $data)
    {
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->thumbnail = $data['thumbnail'];
        $this->type = $data['type'];
        $this->user = $data['user'];
        $this->slug = $data['slug'];
        $this->category = $data['category'];
        $this->shortDescription = $data['shortDescription'];
    }

    /**
     * @return array
     */
    public function getComment()
    {
        return $this->comment;
    }

    public function getFullUser(): UserView
    {
        return $this->user;
    }
}
