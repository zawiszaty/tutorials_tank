<?php

namespace spec\App\Domain\User\Event;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Event\UserMailWasChanged;
use App\Domain\User\Event\UserPasswordWasChanged;
use App\Domain\User\Event\UserWasBanned;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use PhpSpec\ObjectBehavior;

class UserMailWasChangedSpec extends ObjectBehavior
{
    public function let(AggregateRootId $id, Email $email)
    {
        $id->toString()->willReturn('023780a8-be68-11e8-a355-529269fb1459');
        $email->toString()->willReturn('test@wp.pl');

        $this->beConstructedWith(
            $id,
            $email
        );
    }

    public function it_deserialize()
    {
        self::deserialize([
            'id' => '023780a8-be68-11e8-a355-529269fb1459',
            'email' => 'test@wp.pl2',
        ])->shouldBeAnInstanceOf(UserMailWasChanged::class);
    }

    public function it_serialize()
    {
        $this->serialize()->shouldBeArray();
    }
}
