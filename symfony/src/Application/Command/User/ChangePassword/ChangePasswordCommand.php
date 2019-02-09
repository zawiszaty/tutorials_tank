<?php

namespace App\Application\Command\User\ChangePassword;

/**
 * Class ChangePasswordCommand.
 */
class ChangePasswordCommand
{
    /**
     * @var mixed
     */
    public $id;

    /**
     * @var mixed
     */
    public $oldPassword;

    /**
     * @var string
     */
    public $currentPassword;

    /**
     * @var mixed
     */
    public $plainPassword;
}
