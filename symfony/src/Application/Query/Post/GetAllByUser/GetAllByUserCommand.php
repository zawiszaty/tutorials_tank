<?php

namespace App\Application\Query\Post\GetAllByUser;

class GetAllByUserCommand
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
    private $user;

    /**
     * GetAllCommand constructor.
     */
    public function __construct(int $page, int $limit, ?string $query, string $user)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->query = $query;
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

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getUser(): string
    {
        return $this->user;
    }
}
