<?php

namespace App\Infrastructure\Notification;

use App\Infrastructure\User\Query\Projections\UserView;

/**
 * Class NotificationView
 * @package App\Infrastructure\Notification
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
     * @param string $id
     * @param string $content
     * @param string $displayed
     * @param UserView $user
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getDisplayed(): string
    {
        return $this->displayed;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}