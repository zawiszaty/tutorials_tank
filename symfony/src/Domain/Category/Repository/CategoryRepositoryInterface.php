<?php

namespace App\Domain\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Interface CategoryRepositoryInterface.
 */
interface CategoryRepositoryInterface
{
    public function get(AggregateRootId $id): Category;

    public function store(Category $category): void;
}
