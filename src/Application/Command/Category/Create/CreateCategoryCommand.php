<?php

namespace App\Application\Command\Category\Create;

use App\Domain\Category\ValueObject\Name;

/**
 * Class CreateCategoryCommand.
 */
class CreateCategoryCommand
{
    /**
     * @var Name
     */
    private $name;

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
