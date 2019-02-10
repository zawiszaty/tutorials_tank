<?php
/**
 * Created by PhpStorm.
 * User: zawiszaty
 * Date: 01.01.19
 * Time: 12:22.
 */

namespace App\Tests\Application\Command\User\GranteUserAdminRole;

use App\Application\Command\User\Create\CreateUserCommand;
use App\Application\Command\User\GranteUserAdminRole\GranteUserAdminRoleCommand;
use App\Domain\User\Event\UserWasAdminRoleGranted;
use App\Domain\User\Event\UserWasCreated;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Application\Utils\User\User;
use App\Tests\Infrastructure\Share\Event\EventCollectorListener;
use Broadway\Domain\DomainMessage;

class GranteUserAdminRoleHandlerTest extends ApplicationTestCase
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
        $command = new GranteUserAdminRoleCommand();
        $command->userId = $userCreatedEvent->getId()->toString();
        $this
            ->handle($command);
        /** @var EventCollectorListener $collector */
        $collector = $this->service(EventCollectorListener::class);
        /** @var DomainMessage[] $events */
        $events = $collector->popEvents();
        self::assertCount(1, $events);
        /** @var UserWasCreated $userCreatedEvent */
        $userCreatedEvent = $events[0]->getPayload();
        self::assertInstanceOf(UserWasAdminRoleGranted::class, $userCreatedEvent);
    }
}
