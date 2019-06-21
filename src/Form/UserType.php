<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label'=>'Identifiant'])
            ->add('lastName', TextType::class, ['label'=>'Nom'])
            ->add('firstName', TextType::class, ['label'=>'Prénom'])
            ->add('phone', TextType::class, ['label'=>'Téléphone', 'attr' =>['placeholder' => '33_  _ _  _ _  _ _  _ _']])
            ->add('email', EmailType::class, ['label'=>'Adresse email'])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'data_class' => null,
                'label' => 'Changer ma photo de profil',
                'attr' => ['accept' => 'images/*'],
                'required' => false])
            ->add('Valider', SubmitType::class, ['attr' => ['class' => 'hollow button secondary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
