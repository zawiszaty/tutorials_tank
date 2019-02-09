<?php

namespace App\Domain\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Interface CategoryRepositoryInterface.
 */
interface CategoryRepositoryInterface
{
    /**
     * @param AggregateRootId $id
     *
     * @return Category
     */
    public function get(AggregateRootId $id): Category;

    /**
     * @param Category $category
     */
    public function store(Category $category): void;
}
