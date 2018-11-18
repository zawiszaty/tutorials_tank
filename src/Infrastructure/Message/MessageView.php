<?php

namespace App\Infrastructure\Message;

use App\Infrastructure\User\Query\Projections\UserView;

/**
 * Class MessageView.
 */
class MessageView
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
     * @var bool
     */
    private $displayed;

    /**
     * @var UserView
     */
    private $sender;

    /**
     * @var UserView
     */
    private $recipient;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * MessageView constructor.
     *
     * @param string    $id
     * @param string    $content
     * @param bool      $displayed
     * @param UserView  $sender
     * @param UserView  $recipient
     * @param \DateTime $createdAt
     */
    public function __construct(string $id, string $content, bool $displayed, UserView $sender, UserView $recipient, \DateTime $createdAt)
    {
        $this->id = $id;
        $this->content = $content;
        $this->displayed = $displayed;
        $this->sender = $sender;
        $this->recipient = $recipient;
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
     * @return bool
     */
    public function isDisplayed(): bool
    {
        return $this->displayed;
    }

    /**
     * @return string
     */
    public function getSender(): string
    {
        return $this->sender->getId();
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient->getId();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param UserView $sender
     */
    public function setSender(UserView $sender): void
    {
        $this->sender = $sender;
    }
}
