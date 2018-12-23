<?php

namespace App\Application\Query\Message\GetAll;

/**
 * Class GetAllCommand.
 */
class GetAllCommand
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
    private $query;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $user;

    /**
     * GetAllCommand constructor.
     *
     * @param int         $page
     * @param int         $limit
     * @param string|null $query
     * @param string      $recipient
     * @param string      $user
     */
    public function __construct(int $page, int $limit, ?string $query, string $recipient, string $user)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->query = $query;
        $this->recipient = $recipient;
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
     * @return null|array
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }
}
