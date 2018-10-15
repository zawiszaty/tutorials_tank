<?php

namespace App\Application\Query\Category\GetAll;

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
     *
     * @param int $page
     * @param int $limit
     * @param array|null $query
     */
    public function __construct(int $page, int $limit, ?array $query = [])
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->query = $query;
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
     * @return array|null
     */
    public function getQuery(): ?array
    {
        return $this->query;
    }
}
