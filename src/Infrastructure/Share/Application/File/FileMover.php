<?php

namespace App\Infrastructure\Share\Application\File;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Class FileMover
 * @package App\Infrastructure\Share\Application\File
 */
class FileMover
{
    /**
     * @param File $file
     * @return string
     * @throws \Exception
     */
    public static function move(File $file)
    {
        $fileName = FileNameGenerator::generate($file->guessExtension());
        $file->move(
            'thumbnails',
            $fileName
        );

        return $fileName;
    }
}