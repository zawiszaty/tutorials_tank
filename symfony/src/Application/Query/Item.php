<?php

declare(strict_types=1);

namespace App\Application\Query;

/**
 * Class Item.
 */
final class Item
{
    /** @var string */
    public $id;

    /** @var string */
    public $type;

    /** @var array */
    public $resource;

    /** @var array */
    public $relationships = [];

    /** @var mixed */
    public $readModel;

    /**
     * Item constructor.
     *
     * @param $serializableReadModel
     */
    public function __construct($serializableReadModel, array $relations = [])
    {
        $this->id = $serializableReadModel->getId();
        $this->type = $this->type($serializableReadModel);
        $this->resource = $serializableReadModel->serialize();
        $this->relationships = $relations;
        $this->readModel = $serializableReadModel;
    }

    /**
     * @param $model
     */
    private function type($model): string
    {
        $path = explode('\\', \get_class($model));

        return array_pop($path);
    }
}
