<?php

namespace App\Application\Command\User\ChangeName;

use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class ChangeUserNameCommand.
 */
class ChangeUserNameCommand
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;
}
