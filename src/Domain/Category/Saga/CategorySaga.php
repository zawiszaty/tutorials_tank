<?php

namespace App\Domain\Category\Saga;

use App\Domain\Category\Event\CategoryWasDeleted;
use Broadway\CommandHandling\CommandBus;
use Broadway\Saga\Metadata\StaticallyConfiguredSagaInterface;
use Broadway\Saga\Saga;
use Broadway\Saga\State\Criteria;

/**
 * Class CategorySaga.
 */
class CategorySaga extends Saga implements StaticallyConfiguredSagaInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * CategorySaga constructor.
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
            'CategoryWasDeleted' => function (CategoryWasDeleted $event) {
                return new Criteria([
                    'categoryId' => $event->getId(),
                ]);
            },
        ];
    }
}
