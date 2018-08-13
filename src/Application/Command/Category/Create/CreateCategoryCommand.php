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
     * CreateCategoryCommand constructor.
     *
     * @param string $name
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $name)
    {
        $this->name = Name::fromString($name);
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
}
