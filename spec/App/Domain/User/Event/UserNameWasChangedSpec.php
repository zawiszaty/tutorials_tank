<?php

namespace spec\App\Domain\User\Event;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Event\UserNameWasChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\ValueObject\Avatar;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Roles;
use App\Domain\User\ValueObject\Steemit;
use App\Domain\User\ValueObject\UserName;
use PhpSpec\ObjectBehavior;

class UserNameWasChangedSpec extends ObjectBehavior
{
    public function let(AggregateRootId $id, UserName $username, Email $email, Roles $roles, Avatar $avatar, Steemit $steemit, Password $password)
    {
        $id->toString()->willReturn('023780a8-be68-11e8-a355-529269fb1459');
        $username->toString()->willReturn('023780a8-be68-11e8-a355-529269fb1459');
        $email->toString()->willReturn('test@wp.pl');
        $roles->toArray()->willReturn(['test']);
        $avatar->toString()->willReturn('test@wp.pl');
        $steemit->toString()->willReturn('test@wp.pl');
        $password->toString()->willReturn('test@wp.pl');

        $this->beConstructedWith(
            $id,
            $username,
            $email,
            $roles,
            $avatar,
            $steemit,
            false,
            '',
            false
        );
    }

    public function it_deserialize()
    {
        self::deserialize([
            'id'          => 'becc2ada-8e79-11e8-9eb6-529269fb1459',
            'username'    => 'test',
            'email'       => 'test@wp.pl',
            'roles'       => ['test@wp.pl'],
            'avatar'      => 'test@wp.pl',
            'steemit'     => 'test@wp.pl',
            'banned'      => false,
            'password'    => 'test',
            'enabled'      => false,
        ])->shouldBeAnInstanceOf(UserNameWasChanged::class);
    }

    public function it_serialize()
    {
        $this->serialize()->shouldBeArray();
    }
}
