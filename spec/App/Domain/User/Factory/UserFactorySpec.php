<?php

namespace spec\App\Domain\User\Factory;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Avatar;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Roles;
use App\Domain\User\ValueObject\Steemit;
use App\Domain\User\ValueObject\UserName;
use PhpSpec\ObjectBehavior;

class UserFactorySpec extends ObjectBehavior
{
    public function it_create_category(AggregateRootId $id, UserName $username, Email $email, Roles $roles, Avatar $avatar, Steemit $steemit, Password $password)
    {
        $id->toString()->willReturn('023780a8-be68-11e8-a355-529269fb1459');
        $username->toString()->willReturn('023780a8-be68-11e8-a355-529269fb1459');
        $email->toString()->willReturn('test@wp.pl');
        $roles->toArray()->willReturn(['test']);
        $avatar->toString()->willReturn('test@wp.pl');
        $steemit->toString()->willReturn('test@wp.pl');
        $password->toString()->willReturn('test@wp.pl');

        self::create(
            $id,
            $username,
            $email,
            $roles,
            $avatar,
            $steemit,
            false,
            $password
        )->shouldBeAnInstanceOf(User::class);
    }
}
