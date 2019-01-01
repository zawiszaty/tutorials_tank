<?php

namespace App\UI\HTTP\Common\Form;

use App\Infrastructure\Comment\Query\Projections\CommentView;
use App\Infrastructure\Post\Query\Projections\PostView;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class CommentTypeForm.
 */
class CommentTypeForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('parentComment', EntityType::class, [
                'class' => CommentView::class,
                'required' => false,
            ])
            ->add('post', EntityType::class, [
                'class' => PostView::class,
                'required' => false,
                'constraints' => [
                    new NotNull(),
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
        ]);
    }
}
