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

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
    }
}
