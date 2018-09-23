<?php

namespace spec\App\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Avatar;
use PhpSpec\ObjectBehavior;

class AvatarSpec extends ObjectBehavior
{
    public function it_create_from_string()
    {
        self::fromString('test')->shouldBeAnInstanceOf(Avatar::class);
    }

    public function it_to_string()
    {
        $instance = self::fromString('test');
        $instance->toString()->shouldBe('test');
    }
}
