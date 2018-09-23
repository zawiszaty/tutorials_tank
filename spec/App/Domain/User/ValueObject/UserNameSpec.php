<?php

namespace spec\App\Domain\User\ValueObject;

use App\Domain\User\ValueObject\UserName;
use PhpSpec\ObjectBehavior;

class UserNameSpec extends ObjectBehavior
{
    public function it_create_from_string()
    {
        self::fromString('test')->shouldBeAnInstanceOf(UserName::class);
    }

    public function it_to_array()
    {
        $instance = self::fromString('test');
        $instance->toString()->shouldBe('test');
    }
}
