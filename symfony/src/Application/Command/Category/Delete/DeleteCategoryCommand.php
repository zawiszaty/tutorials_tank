<?php

namespace App\Application\Command\Category\Delete;

use App\Domain\Common\ValueObject\AggregateRootId;

/**
 * Class DeleteCategoryCommand.
 */
class DeleteCategoryCommand
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * DeleteCategoryCommand constructor.
     *
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $id)
    {
        $this->id = AggregateRootId::fromString($id);
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }
}
