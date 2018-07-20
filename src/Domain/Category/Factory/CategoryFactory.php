<?php

namespace App\Domain\Category\Factory;

use App\Domain\Category\Category;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;
use Ramsey\Uuid\Uuid;


/**
 * Class CategoryFactory
 * @package App\Domain\Category\Factory
 */
class CategoryFactory
{
    /**
     * @param Name $name
     * @return Category
     * @throws \Assert\AssertionFailedException
     */
    public static function create(Name $name): Category
    {
        $id = AggregatRootId::fromString(Uuid::uuid4());
        $category = Category::create($id, $name);

        return $category;
    }
}