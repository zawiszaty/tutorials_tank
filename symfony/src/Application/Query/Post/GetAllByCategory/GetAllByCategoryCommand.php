<?php

namespace App\Application\Query\Post\GetAllByCategory;

class GetAllByCategoryCommand
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
    private $category;

    /**
     * GetAllCommand constructor.
     */
    public function __construct(int $page, int $limit, ?string $query, string $category)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->query = $query;
        $this->category = $category;
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

    public function getCategory(): string
    {
        return $this->category;
    }
}
