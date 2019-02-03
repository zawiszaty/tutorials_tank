<?php

namespace App\UI\HTTP\Common\Form;

use App\Infrastructure\Category\Query\Projections\CategoryView;
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
class EditPostForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
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
                'class' => CategoryView::class,
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
