<?php

namespace App\Application\Command\Category\Delete;


use App\Domain\Common\ValueObject\AggregatRootId;

class DeleteCategoryCommand
{
    /**
     * @var AggregatRootId
     */
    private $id;

    /**
     * DeleteCategoryCommand constructor.
     * @param string $id
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $id)
    {
        $this->id = AggregatRootId::fromString($id);
    }

    /**
     * @return AggregatRootId
     */
    public function getId(): AggregatRootId
    {
        return $this->id;
    }
}