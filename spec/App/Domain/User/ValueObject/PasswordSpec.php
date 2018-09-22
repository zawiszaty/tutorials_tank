<?php

namespace spec\App\Domain\User\ValueObject;

use App\Domain\Category\ValueObject\Name;
use App\Domain\User\ValueObject\Password;
use PhpSpec\ObjectBehavior;

class PasswordSpec extends ObjectBehavior
{
    public function it_create_from_string()
    {
        self::fromString('test')->shouldBeAnInstanceOf(Password::class);
    }

    public function it_to_string()
    {
        $instance = self::fromString('test123');
        $instance->toString()->shouldBeString();
    }
}
