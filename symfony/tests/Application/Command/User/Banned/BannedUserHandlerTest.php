<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\Banned;

use App\Application\Command\User\BannedUser\BannedUserCommand;
use App\Domain\User\Event\UserWasBanned;
use App\Domain\User\Event\UserWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Application\Utils\User\User;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

/**
 * Class BannedUserHandlerTest.
 */
class BannedUserHandlerTest extends ApplicationTestCase
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
        $command = new BannedUserCommand($userCreatedEvent->getId()->toString());
        $this->handle($command);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var UserWasBanned $userCreatedEvent */
        $userBannedEvent = $events[0]->getPayload();
        self::assertInstanceOf(UserWasBanned::class, $userBannedEvent);
    }
}
