<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categorie;
use App\Entity\Tag;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label'=>'Intitulé de l\'article'])
            ->add('content', TextareaType::class, ['label'=>'Contenu de l\'article'])
            ->add('categorie', EntityType::class, ['class' => Categorie::class, 'choice_label' => 'libelle'])
            ->add('tags', EntityType::class, ['class' => Tag::class, 'choice_label' => 'libelle', 'multiple' => true, 'required' => false])
            ->add('image', FileType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'inherit_data' => true,
        ]);
    }
}
