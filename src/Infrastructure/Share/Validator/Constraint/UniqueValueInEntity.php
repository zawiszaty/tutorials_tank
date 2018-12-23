<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueValueInEntity
 * @package App\Infrastructure\Share\Validator\Constraint
 */
class UniqueValueInEntity extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value is already used.';
    public $entityClass;
    public $field;

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return ['entityClass', 'field'];
    }

    /**
     * @return mixed
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}