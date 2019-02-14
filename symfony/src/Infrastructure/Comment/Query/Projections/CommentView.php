<?php

namespace App\Infrastructure\Comment\Query\Projections;

use App\Domain\Comment\Query\Projections\CommentViewInterface;
use App\Infrastructure\Post\Query\Projections\PostView;
use App\Infrastructure\User\Query\Projections\UserView;
use Broadway\Serializer\Serializable;

/**
 * Class CommentView.
 */
class CommentView implements CommentViewInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var CommentView|null
     */
    private $parrentComment;

    /**
     * @var PostView
     */
    private $post;

    /**
     * @var UserView
     */
    private $user;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @return CommentView|mixed
     */
    public static function deserialize(array $data)
    {
        $instance = new self();
        $instance->id = $data['id'];
        $instance->content = $data['content'];
        $instance->parrentComment = $data['parrentComment'];
        $instance->post = $data['post'];
        $instance->user = $data['user'];
        $instance->createdAt = $data['createdAt'];

        return $instance;
    }

    public function serialize(): array
    {
        return [
            'id'             => $this->getId(),
            'content'        => $this->getContent(),
            'parrentComment' => $this->getParrentComment(),
            'post'           => $this->getPost(),
            'user'           => $this->getUser(),
            'createdAt'      => $this->getCreatedAt()->getTimestamp(),
        ];
    }

    /**
     * @return CommentView
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getParrentComment(): ?string
    {
        if ($this->parrentComment) {
            return $this->parrentComment->getId();
        }

        return null;
    }

    public function getPost(): string
    {
        return $this->post->getId();
    }

    public function getUser(): string
    {
        return $this->user->getId();
    }

    public function getFullUser(): UserView
    {
        return $this->user;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return PostView
     */
    public function getFullPost()
    {
        return $this->post;
    }
}
