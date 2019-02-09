<?php

namespace spec\App\Domain\Category\ValueObject;

use App\Domain\Category\ValueObject\Name;
use PhpSpec\ObjectBehavior;

class NameSpec extends ObjectBehavior
{
    public function it_create_from_string()
    {
        self::fromString('test')->shouldBeAnInstanceOf(Name::class);
    }

    public function it_to_string()
    {
        $instance = self::fromString('test');
        $instance->toString()->shouldBe('test');
    }
}
