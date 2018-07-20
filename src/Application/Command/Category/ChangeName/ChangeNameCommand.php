<?php

namespace App\Application\Command\Category\ChangeName;

use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregatRootId;

class ChangeNameCommand
{
    /**
     * @var AggregatRootId
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    /**
     * ChangeNameHandler constructor.
     * @param string $id
     * @param string $name
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $id, string $name)
    {
        $this->id = AggregatRootId::fromString($id);
        $this->name = Name::fromString($name);
    }

    /**
     * @return AggregatRootId
     */
    public function getId(): AggregatRootId
    {
        return $this->id;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
}