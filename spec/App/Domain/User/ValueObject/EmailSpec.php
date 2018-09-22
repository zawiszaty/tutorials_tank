<?php

namespace spec\App\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Email;
use PhpSpec\ObjectBehavior;

class EmailSpec extends ObjectBehavior
{
    public function it_create_from_string()
    {
        self::fromString('test@wp.pl')->shouldBeAnInstanceOf(Email::class);
    }

    public function it_to_string()
    {
        $instance = self::fromString('test@wp.pl');
        $instance->toString()->shouldBe('test@wp.pl');
    }
}
