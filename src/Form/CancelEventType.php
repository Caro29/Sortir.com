<?php

namespace App\Form;

use App\Entity\CancelEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // dump ($options['idEvent']);

        $builder
            ->add('reason', TextareaType::class, [
                'label' => 'Motif :',
                'attr' => ['rows' => '10'],
            ])
            ->add('relation', TextType::class, [
                'label' => 'Sortie n°',
                'data' => $options['idEvent'],
                'attr' => ['disabled' => true],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CancelEvent::class,
            'idEvent' => '',
        ]);
    }
}
