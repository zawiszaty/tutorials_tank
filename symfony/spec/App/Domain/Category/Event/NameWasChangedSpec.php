<?php

namespace spec\App\Domain\Category\Event;

use App\Domain\Category\Event\NameWasChanged;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;
use PhpSpec\ObjectBehavior;

class NameWasChangedSpec extends ObjectBehavior
{
    public function it_deserialize(AggregateRootId $aggregateRootId, Name $name)
    {
        $this->beConstructedWith(
            $aggregateRootId,
            $name
        );
        self::deserialize([
            'id'   => 'becc2ada-8e79-11e8-9eb6-529269fb1459',
            'name' => 'test',
        ])->shouldBeAnInstanceOf(NameWasChanged::class);
    }

    public function it_serialize(AggregateRootId $aggregateRootId, Name $name)
    {
        $aggregateRootId->toString()->willReturn('test');
        $name->toString()->willReturn('test');
        $this->beConstructedWith(
            $aggregateRootId,
            $name
        );
        $this->serialize()->shouldBeArray();
    }
}
