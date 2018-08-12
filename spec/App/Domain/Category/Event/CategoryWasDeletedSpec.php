<?php

namespace spec\App\Domain\Category\Event;

use App\Domain\Category\Event\CategoryWasDeleted;
use App\Domain\Common\ValueObject\AggregateRootId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CategoryWasDeletedSpec extends ObjectBehavior
{
    function it_deserialize()
    {
        $this->beConstructedWith(
            AggregateRootId::fromString('becc2ada-8e79-11e8-9eb6-529269fb1459')
        );
        self::deserialize([
            'id' => 'becc2ada-8e79-11e8-9eb6-529269fb1459'
        ])->shouldBeAnInstanceOf(CategoryWasDeleted::class);
    }

    function it_serialize()
    {
        $this->beConstructedWith(
            AggregateRootId::fromString('becc2ada-8e79-11e8-9eb6-529269fb1459')
        );
        $this->serialize()->shouldBeArray();
    }
}
