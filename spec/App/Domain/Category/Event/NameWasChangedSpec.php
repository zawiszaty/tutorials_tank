<?php

namespace spec\App\Domain\Category\Event;

use App\Domain\Category\Event\NameWasChanged;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameWasChangedSpec extends ObjectBehavior
{
    function it_deserialize()
    {
        $this->beConstructedWith(
            AggregatRootId::fromString('becc2ada-8e79-11e8-9eb6-529269fb1459'),
            Name::fromString('test')
        );
        self::deserialize([
            'id' => 'becc2ada-8e79-11e8-9eb6-529269fb1459',
            'name' => 'test'
        ])->shouldBeAnInstanceOf(NameWasChanged::class);
    }

    function it_serialize()
    {
        $this->beConstructedWith(
            AggregatRootId::fromString('becc2ada-8e79-11e8-9eb6-529269fb1459'),
            Name::fromString('test')
        );
        $this->serialize()->shouldBeArray();
    }
}
