<?php

namespace App\Infrastructure\Post\Query\Projections;

use App\Domain\User\Query\Projections\UserViewInterface;
use App\Infrastructure\User\Query\Projections\UserView;
use Broadway\Serializer\Serializable;

/**
 * Class PostView
 *
 * @package App\Infrastructure\Post\Query\Projections
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

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'content'          => $this->content,
            'thumbnail'        => $this->thumbnail,
            'type'             => $this->type,
            'user'             => $this->user,
            'slug'             => $this->slug,
            'createdAt'        => $this->createdAt,
            'category'         => $this->category,
            'shortDescription' => $this->shortDescription,
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
        return $this->user->getId();
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
        if ($this->category) {
            return $this->category->getId();
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * @param array $data
     */
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

    /**
     * @return UserView
     */
    public function getFullUser(): UserView
    {
        return $this->user;
    }
}
