<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet', TextType::class, ['label'=>'Sujet du message'])
            ->add('contenu', TextareaType::class, ['label'=>'Contenu du message'])
            ->add('email', EmailType::class, ['label'=>'Votre email'])
            ->add('nom', TextType::class, ['label'=>'Votre nom'])
            // on peut ne pas mettre le submit ici et le mettre directement dans la vue
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
