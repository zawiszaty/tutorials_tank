<?php

namespace spec\App\Domain\User\Assert;

use App\Domain\User\ValueObject\Roles;
use PhpSpec\ObjectBehavior;

class UserIsNotAdminSpec extends ObjectBehavior
{
    public function it_assert_coretly(Roles $roles)
    {
        $roles->toArray()->willReturn(['ROLE_USER']);
        self::check($roles)->shouldBe(null);
    }
}
