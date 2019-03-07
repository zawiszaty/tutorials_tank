<?php

namespace App\UI\HTTP\Common\Form;

use App\Application\Command\User\Create\CreateUserCommand;
use App\Infrastructure\Share\Validator\Constraint\UniqueValueInEntity;
use App\Infrastructure\User\Query\Projections\UserView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class RegistrationFormType.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotNull(),
                    new Length([
                        'max' => '255',
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'max' => '255',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'first_options'   => ['label' => 'form.password'],
                'second_options'  => ['label' => 'form.password_confirmation'],
                'constraints'     => [
                    new NotNull(),
                    new Length([
                        'min' => '6',
                        'max' => '255',
                    ]),
                ],
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var CreateUserCommand $data */
            $data = $event->getForm()->getData();
            $roles = $data->getRoles();
            $roles[] = 'ROLE_USER';
            $data->setRoles($roles);
            $data->setBanned(false);
            $data->setAvatar(null);
            $data->setSteemit(null);
        });
    }

    /**
     * {@inheritdoc}
     */
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
                new UniqueValueInEntity([
                    'field'       => 'email',
                    'entityClass' => UserView::class,
                    'message'     => 'Istnieje juz konto przypisane do tego adresu email',
                ]),
            ],
        ]);
    }
}
