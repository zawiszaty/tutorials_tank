<?php

namespace App\Application\Query\User\GetAll;

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
     * @var array
     */
    private $query;

    /**
     * GetAllCommand constructor.
     *
     * @param int         $page
     * @param int         $limit
     * @param null|string $query
     */
    public function __construct(int $page, int $limit, ?array $query = null)
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
     * @return null|array
     */
    public function getQuery(): ?array
    {
        return $this->query;
    }
}
