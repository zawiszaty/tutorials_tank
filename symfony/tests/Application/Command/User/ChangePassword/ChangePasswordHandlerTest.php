<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\ChangePassword;

use App\Application\Command\User\ChangePassword\ChangePasswordCommand;
use App\Domain\User\Event\UserPasswordWasChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Infrastructure\Share\Application\Password\PasswordEncoder;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Application\Utils\User\User;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

class ChangePasswordHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $email = 'asd@asd.asd';
        $command = User::create($email);
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var UserWasCreated $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();
        self::assertInstanceOf(UserWasCreated::class, $userCreatedEvent);

        $command = new ChangePasswordCommand();
        $command->id = $userCreatedEvent->getId()->toString();
        $command->plainPassword = 'test2';
        $command->currentPassword = PasswordEncoder::encode('test');
        $command->oldPassword = 'test';
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var UserPasswordWasChanged $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();
        self::assertInstanceOf(UserPasswordWasChanged::class, $userCreatedEvent);
    }
}
