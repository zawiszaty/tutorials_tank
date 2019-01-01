<?php

namespace spec\App\Domain\User\Event;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Event\UserPasswordWasChanged;
use App\Domain\User\ValueObject\Password;
use PhpSpec\ObjectBehavior;

class UserPasswordWasChangedSpec extends ObjectBehavior
{
    public function let(AggregateRootId $id, Password $password)
    {
        $id->toString()->willReturn('023780a8-be68-11e8-a355-529269fb1459');
        $password->toString()->willReturn('023780a8-be68-11e8-a355-529269fb1459');

        $this->beConstructedWith(
            $id,
            $password
        );
    }

    public function it_deserialize()
    {
        self::deserialize([
            'id' => '023780a8-be68-11e8-a355-529269fb1459',
            'password' => '023780a8-be68-11e8-a355-529269fb1459',
        ])->shouldBeAnInstanceOf(UserPasswordWasChanged::class);
    }

    public function it_serialize()
    {
        $this->serialize()->shouldBeArray();
    }
}
