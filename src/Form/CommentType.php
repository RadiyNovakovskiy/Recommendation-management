<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, array(
                'label' => 'Комментарий: ',
                'attr' =>[
                    'placeholder' => 'Введите комментарий...'
                ]
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'save',
                'attr' => [
                    'class'=> 'btn btn-success float-left mr-3',
                ]
            ))
            ->add('delete', SubmitType::class, array(
                'label' => 'delete', 
                'attr' => [
                    'class' => 'btn btn-danger',
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
