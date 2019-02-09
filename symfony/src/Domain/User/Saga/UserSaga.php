<?php

namespace App\Domain\User\Saga;

use App\Application\Command\User\SendEmail\SendEmailCommand;
use App\Domain\User\Event\UserWasCreated;
use Broadway\Saga\Metadata\StaticallyConfiguredSagaInterface;
use Broadway\Saga\Saga;
use Broadway\Saga\State;
use League\Tactician\CommandBus;

/**
 * Class UserSaga.
 */
class UserSaga extends Saga implements StaticallyConfiguredSagaInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * UserSaga constructor.
     *
     * @param CommandBus $commandBus
     */
    public function __construct(
        CommandBus $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    /**
     * @return array
     */
    public static function configuration()
    {
        return [
            'App\Domain\User\Event\UserWasCreated' => function (UserWasCreated $userWasCreated) {
            },
        ];
    }

    /**
     * @param UserWasCreated $userWasCreated
     * @param State          $state
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return State
     */
    public function handleUserWasCreated(UserWasCreated $userWasCreated, State $state): State
    {
        $state->set('id', $userWasCreated->getId());
        $state->set('confirmationToken', $userWasCreated->getConfirmationToken());
        $sendEmailCommand = new SendEmailCommand($userWasCreated->getEmail(), $userWasCreated->getConfirmationToken());
        $this->commandBus->handle($sendEmailCommand);

        return $state;
    }
}
