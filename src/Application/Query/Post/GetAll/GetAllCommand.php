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
     *
     * @param int         $page
     * @param int         $limit
     * @param string|null $query
     */
    public function __construct(int $page, int $limit, ?string $query = null)
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
     * @return null|string
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }
}
