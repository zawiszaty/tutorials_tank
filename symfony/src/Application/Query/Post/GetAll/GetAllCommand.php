<?php

namespace App\Application\Query\Post\GetAll;

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
     * GetAllCommand constructor.
     */
    public function __construct(int $page, int $limit, ?string $query = null)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->query = $query;
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
}
