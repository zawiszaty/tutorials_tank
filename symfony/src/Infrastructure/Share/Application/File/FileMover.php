<?php

namespace App\Infrastructure\Share\Application\File;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Class FileMover.
 */
class FileMover
{
    /**
     * @throws \Exception
     *
     * @return string
     */
    public static function move(File $file, string $directory)
    {
        $fileName = FileNameGenerator::generate($file->guessExtension());
        $file->move(
            $directory,
            $fileName
        );

        return $fileName;
    }
}
