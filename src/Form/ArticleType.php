<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label'=>'Intitulé de l\'article'])
            ->add('date_publi', DateTimeType::class, ['label'=>'Date de publication'])
            ->add('author', TextType::class, ['label'=>'Auteur de l\'article'])
            ->add('content', TextareaType::class, ['label'=>'Contenu de l\'article'])
            ->add('enregistrer', SubmitType::class, ['attr'=>['class'=>'btn-info']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}