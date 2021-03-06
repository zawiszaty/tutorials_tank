<?php

namespace App\Infrastructure\Share\Application\File;

use Ramsey\Uuid\Uuid;

/**
 * Class FileNameGenerator.
 */
class FileNameGenerator
{
    /**
     * @throws \Exception
     *
     * @return string
     */
    public static function generate(string $extention)
    {
        $name = Uuid::uuid4() . '-' . Uuid::uuid4() . '.' . $extention;

        return $name;
    }
}
