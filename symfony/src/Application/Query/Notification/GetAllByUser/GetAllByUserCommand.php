<?php

namespace App\Application\Query\Notification\GetAllByUser;

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
    private $sort;

    /**
     * GetAllCommand constructor.
     */
    public function __construct(int $page, int $limit, string $sort = 'asc', ?string $query = null)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->query = $query;
        $this->sort = $sort;
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

    public function getSort(): string
    {
        return $this->sort;
    }
}
