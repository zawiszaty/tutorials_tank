<?php

namespace spec\App\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Steemit;
use PhpSpec\ObjectBehavior;

class SteemitSpec extends ObjectBehavior
{
    public function it_create_from_string()
    {
        self::fromString('test')->shouldBeAnInstanceOf(Steemit::class);
    }

    public function it_to_array()
    {
        $instance = self::fromString('test');
        $instance->toString()->shouldBe('test');
    }
}
