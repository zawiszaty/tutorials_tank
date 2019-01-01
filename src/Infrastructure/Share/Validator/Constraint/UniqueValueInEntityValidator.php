<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Validator\Constraint;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueValueInEntityValidator.
 */
class UniqueValueInEntityValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UniqueValueInEntityValidator constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $entityRepository = $this->em->getRepository($constraint->entityClass);

        if (!is_scalar($constraint->field)) {
            throw new InvalidArgumentException('"field" parameter should be any scalar type');
        }

        $searchResults = $entityRepository->findBy([
            $constraint->field => $value->{$constraint->field},
        ]);

        if (count($searchResults) > 0) {
            $constraint->message = 'Value ' . $constraint->field . ' is already used.';
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
