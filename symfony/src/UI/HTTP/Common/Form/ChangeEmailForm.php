<?php

namespace App\UI\HTTP\Common\Form;

use App\Infrastructure\Share\Validator\Constraint\UniqueValueInEntity;
use App\Infrastructure\User\Query\Projections\UserView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CategoryType.
 */
class ChangeEmailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'constraints'     => [
                new UniqueValueInEntity([
                    'field'       => 'email',
                    'entityClass' => UserView::class,
                    'message'     => 'Istnieje juz konto przypisane do tego adresu email',
                ]),
            ],
        ]);
    }
}
