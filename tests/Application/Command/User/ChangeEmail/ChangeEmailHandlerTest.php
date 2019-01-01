<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\ChangeEmail;

use App\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use App\Application\Command\User\Create\CreateUserCommand;
use App\Domain\User\Event\UserMailWasChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

class ChangeEmailHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     */
    public function command_handler_must_fire_domain_event(): void
    {
        $email = 'asd@asd.asd';
        $command = new CreateUserCommand();
        $command->setAvatar('test');
        $command->setBanned(false);
        $command->setEmail($email);
        $command->setPlainPassword('test');
        $command->setUsername('test');
        $command->setRoles(['ROLE_USER']);
        $command->setSteemit('test');

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
        $command = new ChangeEmailCommand();
        $command->setId($userCreatedEvent->getId()->toString());
        $command->setEmail('newmail@wp.pl');
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var UserMailWasChanged $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();
        self::assertInstanceOf(UserMailWasChanged::class, $userCreatedEvent);
    }
}
