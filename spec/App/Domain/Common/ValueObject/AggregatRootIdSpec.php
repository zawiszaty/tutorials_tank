<?php

namespace spec\App\Domain\Common\ValueObject;

use App\Domain\Common\ValueObject\AggregatRootId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AggregatRootIdSpec extends ObjectBehavior
{
    function it_create_from_string()
    {
        self::fromString('d156b590-8cca-11e8-9eb6-529269fb1459')->shouldBeAnInstanceOf(AggregatRootId::class);
    }

    function it_to_string()
    {
        $instance = self::fromString('d156b590-8cca-11e8-9eb6-529269fb1459');
        $instance->toString()->shouldBe('d156b590-8cca-11e8-9eb6-529269fb1459');
    }
}
