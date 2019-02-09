<?php

namespace spec\App\Domain\Category\Event;

use App\Domain\Category\Event\CategoryWasDeleted;
use App\Domain\Common\ValueObject\AggregateRootId;
use PhpSpec\ObjectBehavior;

class CategoryWasDeletedSpec extends ObjectBehavior
{
    public function it_deserialize(AggregateRootId $aggregateRootId)
    {
        $this->beConstructedWith(
            $aggregateRootId
        );
        self::deserialize([
            'id' => 'becc2ada-8e79-11e8-9eb6-529269fb1459',
        ])->shouldBeAnInstanceOf(CategoryWasDeleted::class);
    }

    public function it_serialize(AggregateRootId $aggregateRootId)
    {
        $aggregateRootId->toString()->willReturn('test');
        $this->beConstructedWith(
            $aggregateRootId
        );
        $this->serialize()->shouldBeArray();
    }
}
