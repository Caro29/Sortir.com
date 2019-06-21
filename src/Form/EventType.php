<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la sortie'])
            ->add('start', DateTimeType::class, ['widget' => 'single_text', 'label' => 'Date et heure de la sortie'])
            ->add('limitDate', DateTimeType::class, ['widget' => 'single_text', 'label' => 'Date et heure limite d\'inscription'])
            ->add('nbMax', IntegerType::class, [
                'label' => 'Nombre de participants maximum',
            ])
            ->add('duration', DateIntervalType::class, ['label' => 'Durée',
                'labels' => [
                    'days' => 'Jours',
                    'hours' => 'Heures',
                    'minutes' => 'Minutes'
                ],
                'with_years' => false,
                'with_months' => false,
                'with_weeks' => false,
                'with_days' => true,
                'with_hours' => true,
                'with_minutes' => true,
                'with_seconds' => false])
            ->add('description', TextareaType::class, ['label' => 'Description de la sortie'])
            // Photo de l'Event
            ->add('picture', FileType::class, [
                'mapped' => false,
                'label' => 'Photo',
                'attr' => ['accept' => 'images/*'],
                'required' => false,
                'data_class' => null,
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'mapped' => false,
                'data' => $options['address'],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'mapped' => false,
                'data' => $options['city'],
            ])
            ->add('enregistrer', SubmitType::class, [
                'attr' => ['class' => 'hollow button secondary']
            ])
            ->add('publier', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr' => ['class' => 'hollow button secondary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'address' => '',
            'city' => '',
            'idEvent' => '',
            'data_class' => Event::class,

        ]);
    }
}
