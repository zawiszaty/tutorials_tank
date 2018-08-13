<?php

namespace App\Domain\Category\Event;

use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Deleted;
use Assert\Assertion;

/**
 * Class CategoryWasCreated.
 */
class CategoryWasCreated extends AbstractEvent
{
    /**
     * @param array $data
     *
     * @throws \Assert\AssertionFailedException
     *
     * @return CategoryWasCreated
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'name');
        Assertion::keyExists($data, 'deleted');

        return new self(
            AggregateRootId::fromString($data['id']),
            Name::fromString($data['name']),
            Deleted::fromString($data['deleted'])
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id'      => $this->id->toString(),
            'name'    => $this->name->toString(),
            'deleted' => $this->deleted->toBool(),
        ];
    }

    /**
     * CategoryWasCreated constructor.
     *
     * @param AggregateRootId $id
     * @param Name            $name
     * @param Deleted         $deleted
     */
    public function __construct(AggregateRootId $id, Name $name, Deleted $deleted)
    {
        $this->id = $id;
        $this->name = $name;
        $this->deleted = $deleted;
    }

    /**
     * @var Name
     */
    private $name;

    /**
     * @var Deleted
     */
    private $deleted;

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return Deleted
     */
    public function getDeleted(): Deleted
    {
        return $this->deleted;
    }
}
