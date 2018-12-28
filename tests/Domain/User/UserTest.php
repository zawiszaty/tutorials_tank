<?php

declare(strict_types=1);

namespace App\Tests\Domain\User;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Event\UserAvatarWasChanged;
use App\Domain\User\Event\UserMailWasChanged;
use App\Domain\User\Event\UserNameWasChanged;
use App\Domain\User\Event\UserWasBanned;
use App\Domain\User\Event\UserWasConfirmed;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Avatar;
use App\Domain\User\ValueObject\ConfirmationToken;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Roles;
use App\Domain\User\ValueObject\Steemit;
use App\Domain\User\ValueObject\UserName;
use Broadway\Domain\DomainMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class UserTest
 * @package App\Tests\Domain\User
 */
class UserTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_valid_email_it_should_create_a_user_instance(): void
    {
        $emailString = 'lol@aso.maximo';
        /** @var User $user */
        $user = User::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            UserName::fromString('test'),
            Email::fromString($emailString),
            Roles::fromString([
                'ROLE_USER'
            ]),
            Avatar::fromString('test.jpg'),
            Steemit::fromString('test'),
            false,
            Password::formHash('12312313'),
            ConfirmationToken::fromString('12313')
        );
        self::assertSame($emailString, $user->getEmail()->toString());
        self::assertNotNull($user->getId());
        $events = $user->getUncommittedEvents();
        self::assertCount(1, $events->getIterator(), 'Only one event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->current();
        self::assertInstanceOf(UserWasCreated::class, $event->getPayload(), 'First event should be UserWasCreated');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_new_email_it_should_change_if_not_eq_to_prev_email(): void
    {
        $emailString = 'lol@aso.maximo';
        /** @var User $user */
        $user = User::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            UserName::fromString('test'),
            Email::fromString($emailString),
            Roles::fromString([
                'ROLE_USER'
            ]),
            Avatar::fromString('test.jpg'),
            Steemit::fromString('test'),
            false,
            Password::formHash('12312313'),
            ConfirmationToken::fromString('12313')
        );

        $newEmail = 'weba@aso.maximo';
        $user->changeEmail($newEmail);
        self::assertSame($user->getEmail()->toString(), $newEmail, 'Emails should be equals');
        $events = $user->getUncommittedEvents();
        self::assertCount(2, $events->getIterator(), '2 event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertInstanceOf(UserMailWasChanged::class, $event->getPayload(), 'Second event should be UserEmailChanged');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_new_name_it_should_change_if_not_eq_to_prev_name()
    {
        $emailString = 'lol@aso.maximo';
        /** @var User $user */
        $user = User::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            UserName::fromString('test'),
            Email::fromString($emailString),
            Roles::fromString([
                'ROLE_USER'
            ]),
            Avatar::fromString('test.jpg'),
            Steemit::fromString('test'),
            false,
            Password::formHash('12312313'),
            ConfirmationToken::fromString('12313')
        );

        $name = 'test2';
        $user->changeName($name);
        self::assertSame($user->getUsername()->toString(), $name, 'Name should be equals');
        $events = $user->getUncommittedEvents();
        self::assertCount(2, $events->getIterator(), '2 event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertInstanceOf(UserNameWasChanged::class, $event->getPayload(), 'Second event should be UserEmailChanged');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_new_avatar_it_should_change_if_not_eq_to_prev_avatar()
    {
        $emailString = 'lol@aso.maximo';
        /** @var User $user */
        $user = User::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            UserName::fromString('test'),
            Email::fromString($emailString),
            Roles::fromString([
                'ROLE_USER'
            ]),
            Avatar::fromString('test.jpg'),
            Steemit::fromString('test'),
            false,
            Password::formHash('12312313'),
            ConfirmationToken::fromString('12313')
        );

        $avatar = 'test2.jpg';
        $user->changeAvatar($avatar);
        self::assertSame($user->getAvatar()->toString(), $avatar, 'Avatar should be equals');
        $events = $user->getUncommittedEvents();
        self::assertCount(2, $events->getIterator(), '2 event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertInstanceOf(UserAvatarWasChanged::class, $event->getPayload(), 'Second event should be UserEmailChanged');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function confirm_user()
    {
        $emailString = 'lol@aso.maximo';
        /** @var User $user */
        $user = User::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            UserName::fromString('test'),
            Email::fromString($emailString),
            Roles::fromString([
                'ROLE_USER'
            ]),
            Avatar::fromString('test.jpg'),
            Steemit::fromString('test'),
            false,
            Password::formHash('12312313'),
            ConfirmationToken::fromString('12313')
        );

        $user->confirm();
        self::assertSame($user->isEnabled(), true, 'Enabled should be equals');
        $events = $user->getUncommittedEvents();
        self::assertCount(2, $events->getIterator(), '2 event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertInstanceOf(UserWasConfirmed::class, $event->getPayload(), 'Second event should be UserWasConfirmed');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function banned_user()
    {
        $emailString = 'lol@aso.maximo';
        /** @var User $user */
        $user = User::create(
            AggregateRootId::fromString(Uuid::uuid4()->toString()),
            UserName::fromString('test'),
            Email::fromString($emailString),
            Roles::fromString([
                'ROLE_USER'
            ]),
            Avatar::fromString('test.jpg'),
            Steemit::fromString('test'),
            false,
            Password::formHash('12312313'),
            ConfirmationToken::fromString('12313')
        );

        $user->banned();
        self::assertSame($user->isBanned(), true, 'Banned should be equals');
        $events = $user->getUncommittedEvents();
        self::assertCount(2, $events->getIterator(), '2 event should be in the buffer');
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet(1);
        self::assertInstanceOf(UserWasBanned::class, $event->getPayload(), 'Second event should be UserWasConfirmed');
    }
}