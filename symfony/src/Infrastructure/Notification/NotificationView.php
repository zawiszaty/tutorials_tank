<?php

namespace App\Infrastructure\Notification;

use App\Infrastructure\User\Query\Projections\UserView;

/**
 * Class NotificationView.
 */
class NotificationView
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $displayed;

    /**
     * @var UserView
     */
    protected $user;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * NotificationView constructor.
     */
    public function __construct(string $id, string $content, string $displayed, UserView $user, string $type, \DateTime $createdAt)
    {
        $this->id = $id;
        $this->content = $content;
        $this->displayed = $displayed;
        $this->user = $user;
        $this->type = $type;
        $this->createdAt = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDisplayed(): string
    {
        return $this->displayed;
    }

    public function getUser(): string
    {
        return $this->user->getId();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function serialize(): array
    {
        return [
            'id'        => $this->id,
            'content'   => $this->content,
            'displayed' => $this->displayed,
            'user'      => $this->user->getId(),
            'type'      => $this->type,
            'createdAt' => $this->createdAt->getTimestamp(),
        ];
    }

    public function view()
    {
        $this->displayed = true;
    }
}
