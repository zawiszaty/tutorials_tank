<?php

namespace App\UI\HTTP\Common\Form;

use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Share\Validator\Constraint\UniqueValueInEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class CategoryType.
 */
class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'min' => '1',
                        'max' => '20',
                    ]),
                ],
                'documentation' => [
                    'type'        => 'string', // would have been automatically detected in this case
                    'description' => 'Category name.',
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'constraints'     => [
                new UniqueValueInEntity([
                    'field'       => 'name',
                    'entityClass' => CategoryView::class,
                ]),
            ],
        ]);
    }
}
