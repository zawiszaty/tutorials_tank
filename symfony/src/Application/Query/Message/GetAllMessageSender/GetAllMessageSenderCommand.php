<?php

namespace App\Application\Query\Message\GetAllMessageSender;

/**
 * Class GetAllMessageSenderCommand.
 */
class GetAllMessageSenderCommand
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $user;

    /**
     * GetAllMessageSenderCommand constructor.
     */
    public function __construct(int $page, int $limit, string $user)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->user = $user;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getUser(): string
    {
        return $this->user;
    }
}
