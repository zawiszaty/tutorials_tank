<?php

namespace App\Application\Command\Category\ChangeName;

use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class ChangeNameCommand
 * @package App\Application\Command\Category\ChangeName
 */
class ChangeNameCommand
{
    /**
     * @var AggregateRootId
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
        $this->id = AggregateRootId::fromString($id);
        $this->name = Name::fromString($name);
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
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