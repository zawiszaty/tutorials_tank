<?php

namespace App\Domain\Category\Factory;

use App\Domain\Category\Category;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;
use Ramsey\Uuid\Uuid;

/**
 * Class CategoryFactory.
 */
class CategoryFactory
{
    /**
     * @param Name $name
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     *
     * @return Category
     */
    public static function create(Name $name): Category
    {
        $category = Category::create(AggregateRootId::fromString(Uuid::uuid4()), $name);

        return $category;
    }
}
