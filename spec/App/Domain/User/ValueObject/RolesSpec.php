<?php

namespace spec\App\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Roles;
use PhpSpec\ObjectBehavior;

class RolesSpec extends ObjectBehavior
{
    public function it_create_from_string()
    {
        self::fromString(['test'])->shouldBeAnInstanceOf(Roles::class);
    }

    public function it_to_array()
    {
        $instance = self::fromString(['test']);
        $instance->toArray()->shouldBe(['test']);
    }
}
