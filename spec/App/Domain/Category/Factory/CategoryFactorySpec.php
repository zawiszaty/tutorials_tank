<?php

namespace spec\App\Domain\Category\Factory;

use App\Domain\Category\Category;
use App\Domain\Category\Factory\CategoryFactory;
use App\Domain\Category\ValueObject\Name;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CategoryFactorySpec extends ObjectBehavior
{
    function it_create_category()
    {
        self::create(Name::fromString('test'))->shouldBeAnInstanceOf(Category::class);
    }
}
