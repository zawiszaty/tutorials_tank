<?php

namespace App\Application\Command\User\ChangeAvatar;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ChangeAvatarCommand.
 */
class ChangeAvatarCommand
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var File|null
     */
    private $file;

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param null|File $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }
}
