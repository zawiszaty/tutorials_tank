<?php

namespace App\UI\HTTP\Common\Form;

use App\Infrastructure\Share\Validator\Constraint\UniqueValueInEntity;
use App\Infrastructure\User\Query\Projections\UserView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class CategoryType.
 */
class ChangeUserNameForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'min' => '1',
                        'max' => '20',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'constraints'     => [
                new UniqueValueInEntity([
                    'field'       => 'username',
                    'entityClass' => UserView::class,
                    'message'     => 'Nazwa jest juz zajęta',
                ]),
            ],
        ]);
    }
}
