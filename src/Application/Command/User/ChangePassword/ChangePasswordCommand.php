<?php

namespace App\Application\Command\User\ChangePassword;

/**
 * Class ChangePasswordCommand
 * @package App\Application\Command\User\ChangePassword
 */
class ChangePasswordCommand
{
    /**
     * @var mixed
     */
    private $id;
    /**
     * @var mixed
     */
    private $oldPassword;
    /**
     * @var mixed
     */
    private $plainPassword;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}