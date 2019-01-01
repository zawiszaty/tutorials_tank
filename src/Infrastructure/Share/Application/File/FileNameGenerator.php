<?php

namespace App\Infrastructure\Share\Application\File;

use Ramsey\Uuid\Uuid;

/**
 * Class FileNameGenerator.
 */
class FileNameGenerator
{
    /**
     * @param string $extention
     *
     * @return string
     *
     * @throws \Exception
     */
    public static function generate(string $extention)
    {
        $name = Uuid::uuid4().'-'.Uuid::uuid4().'.'.$extention;

        return $name;
    }
}
