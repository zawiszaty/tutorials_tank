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
     *
     * @param int    $page
     * @param int    $limit
     * @param string $query
     * @param string $recipient
     * @param string $user
     */
    public function __construct(int $page, int $limit, string $user)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }
}
