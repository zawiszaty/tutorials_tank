<?php

namespace App\Domain\User\Saga;

use App\Application\Command\User\SendEmail\SendEmailCommand;
use App\Domain\User\Event\UserWasCreated;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Broadway\Saga\Metadata\StaticallyConfiguredSagaInterface;
use Broadway\Saga\Saga;
use Broadway\Saga\State;
use Broadway\Saga\State\Criteria;
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
     * @var MysqlUserReadModelRepository
     */
    private $mysqlUserReadModelRepository;

    /**
     * UserSaga constructor.
     *
     * @param CommandBus                   $commandBus
     * @param MysqlUserReadModelRepository $mysqlUserReadModelRepository
     */
    public function __construct(
        CommandBus $commandBus,
        MysqlUserReadModelRepository $mysqlUserReadModelRepository
    ) {
        $this->commandBus = $commandBus;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
    }

    /**
     * @return array
     */
    public static function configuration()
    {
        return [
            'UserWasCreated' => function (UserWasCreated $userWasCreated) {
                return new Criteria([
                    'id' => $userWasCreated->getId(),
                ]);
            },
        ];
    }

    /**
     * @param UserWasCreated $userWasCreated
     * @param State          $state
     *
     * @return State
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Assert\AssertionFailedException
     */
    public function handleUserWasCreated(UserWasCreated $userWasCreated, State $state): State
    {
        $state->set('id', $userWasCreated->getId());
        /** @var UserView $user */
        $user = $this->mysqlUserReadModelRepository->oneByEmail($userWasCreated->getEmail());
        $sendEmailCommand = new SendEmailCommand($userWasCreated->getEmail(), $user->getConfirmationToken());
        $this->commandBus->handle($sendEmailCommand);

        return $state;
    }
}
