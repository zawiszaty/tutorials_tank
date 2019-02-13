<?php
/**
 * Created by PhpStorm.
 * User: zawiszaty
 * Date: 10.02.19
 * Time: 12:53.
 */

namespace App\Tests\Application\Utils\Category;

use App\Application\Command\Category\Create\CreateCategoryCommand;

/**
 * Class Category.
 */
class Category
{
    public static function create(string $name): CreateCategoryCommand
    {
        $command = new CreateCategoryCommand();
        $command->name = $name;

        return $command;
    }
}
