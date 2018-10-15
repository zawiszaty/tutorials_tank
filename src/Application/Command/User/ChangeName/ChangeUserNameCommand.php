<?php

namespace App\Application\Command\User\ChangeName;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\ValueObject\UserName;

/**
 * Class ChangeUserNameCommand
 * @package App\Application\Command\User\ChangeName
 */
class ChangeUserNameCommand
{
    /**
     * @var AggregateRootId|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @return AggregateRootId|null
     */
    public function getId(): ?AggregateRootId
    {
        return $this->id;
    }

    /**
     * @param AggregateRootId|null $id
     */
    public function setId(?AggregateRootId $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}