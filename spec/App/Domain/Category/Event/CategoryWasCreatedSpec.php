<?php

namespace spec\App\Domain\Category\Event;

use App\Domain\Category\Event\CategoryWasCreated;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Deleted;
use PhpSpec\ObjectBehavior;

class CategoryWasCreatedSpec extends ObjectBehavior
{
    public function it_deserialize()
    {
        $this->beConstructedWith(
            AggregateRootId::fromString('becc2ada-8e79-11e8-9eb6-529269fb1459'),
            Name::fromString('test'),
            Deleted::fromString(0)
        );
        self::deserialize([
            'id'      => 'becc2ada-8e79-11e8-9eb6-529269fb1459',
            'name'    => 'test',
            'deleted' => 0,
        ])->shouldBeAnInstanceOf(CategoryWasCreated::class);
    }

    public function it_serialize()
    {
        $this->beConstructedWith(
            AggregateRootId::fromString('becc2ada-8e79-11e8-9eb6-529269fb1459'),
            Name::fromString('test'),
            Deleted::fromString(0)
        );
        $this->serialize()->shouldBeArray();
    }
}
