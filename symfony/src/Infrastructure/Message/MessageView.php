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

    public function getId(): string
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isDisplayed(): bool
    {
        return $this->displayed;
    }

    public function getSender(): string
    {
        return $this->sender->getId();
    }

    public function getRecipient(): string
    {
        return $this->recipient->getId();
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setSender(UserView $sender): void
    {
        $this->sender = $sender;
    }

    public function setDisplayed(): void
    {
        $this->displayed = true;
    }

    public function serialize(): array
    {
        return [
            'id'        => $this->id,
            'content'   => $this->content,
            'displayed' => $this->displayed,
            'sender'    => $this->sender->getId(),
            'recipient' => $this->recipient->getId(),
            'createdAt' => $this->createdAt->getTimestamp(),
        ];
    }
}
