<?php

namespace spec\App\Domain\User\Event;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Event\UserWasConfirmed;
use PhpSpec\ObjectBehavior;

class UserWasConfirmedSpec extends ObjectBehavior
{
    public function let(AggregateRootId $id)
    {
        $id->toString()->willReturn('023780a8-be68-11e8-a355-529269fb1459');

        $this->beConstructedWith(
            $id,
            false
        );
    }

    public function it_deserialize()
    {
        self::deserialize([
            'id'           => 'becc2ada-8e79-11e8-9eb6-529269fb1459',
            'enabled'      => false,
        ])->shouldBeAnInstanceOf(UserWasConfirmed::class);
    }

    public function it_serialize()
    {
        $this->serialize()->shouldBeArray();
    }
}
