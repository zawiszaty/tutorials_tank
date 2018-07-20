<?php

namespace App\Domain\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Common\ValueObject\AggregatRootId;

interface CategoryReposiotryInterface
{
    public function get(AggregatRootId $id): Category;

    public function store(Category $category): void;
}