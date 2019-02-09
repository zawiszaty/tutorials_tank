<?php

namespace spec\App\Domain\Category\Factory;

use App\Domain\Category\Category;
use App\Domain\Category\ValueObject\Name;
use PhpSpec\ObjectBehavior;

class CategoryFactorySpec extends ObjectBehavior
{
    public function it_create_category(Name $name)
    {
        self::create($name)->shouldBeAnInstanceOf(Category::class);
    }
}
