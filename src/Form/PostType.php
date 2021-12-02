<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, array(
                'label' => 'Изображение', 
                'required' => 'false',
                'mapped' => 'false',
                'data_class' => null,
            ))
            ->add('category', EntityType::class, array(
                'label' => 'Категория',
                'placeholder' => 'Категория...',
                'class' => Category::class,
                'choice_label' => 'title',
            ))
            ->add('title', TextType::class, array(
                'label' => 'Название поста',
                'attr' => [
                    'placeholder' => 'Название...'
                ]
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Описание',
                'attr' => [
                    'placeholder' => 'Описание...'
                ]
            ))

            ->add('rating',  ChoiceType::class, array(
                'label' => 'Оценка',    
                'choices' => ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10] 
            ))
            
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn btn-success float-left mr-3'
                ]
            ))
            ->add('delete', SubmitType::class, array(
                'label' => 'Удалить',
                'attr' => [
                    'class' => 'btn btn-danger'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
