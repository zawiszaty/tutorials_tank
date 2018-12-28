<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\ChangeName;

use App\Application\Command\Category\ChangeName\ChangeNameCommand;
use App\Application\Command\User\ChangeName\ChangeUserNameCommand;
use App\Application\Command\User\Create\CreateUserCommand;
use App\Domain\Category\Event\NameWasChanged;
use App\Domain\User\Event\UserNameWasChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

class ChangeNameHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
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
        $command = new ChangeUserNameCommand();
        $command->name = 'test';
        $command->id = $userCreatedEvent->getId()->toString();
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var UserNameWasChanged $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();
        self::assertInstanceOf(UserNameWasChanged::class, $userCreatedEvent);
    }

}