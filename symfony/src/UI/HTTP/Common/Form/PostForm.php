<?php

namespace App\UI\HTTP\Common\Form;

use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Post\Query\Projections\PostView;
use App\Infrastructure\Share\Validator\Constraint\UniqueValueInEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class PostForm.
 */
class PostForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ])
            ->add('content', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ])
            ->add('file', FileType::class, [
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ])
            ->add('type', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new Choice([
                        'oder_site',
                        'own_post',
                    ]),
                ],
                'required' => true,
            ])
            ->add('category', EntityType::class, [
                'class'       => CategoryView::class,
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ])->add('shortDescription', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'constraints'     => [
                new UniqueValueInEntity([
                    'field'       => 'title',
                    'entityClass' => PostView::class,
                    'message'     => 'Ten tytuł jest już zajęty',
                ]),
            ],
        ]);
    }
}
