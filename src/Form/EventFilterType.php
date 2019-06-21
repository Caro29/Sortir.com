<?php
/**
 * Created by PhpStorm.
 * User: onoel2018
 * Date: 08/04/2019
 * Time: 10:09
 */

namespace App\Form;


use App\Entity\Campus;
use App\Entity\Event;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Campus de l'Event
        $builder->add('name', EntityType::class, [
            'class' => Campus::class,
            //Requete avec le query builder
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
            },
            'choice_label' => 'name',
            'label' => 'Campus', 'placeholder' => 'Tous', 'required' => false
        ]);
        // Bar de recherche
        $builder->add('search', SearchType::class, [
            'label' => 'Le nom de la sortie contient : ', 'attr' => ['class' => 'animated-search-form', 'placeholder' => 'Rechercher...'], 'required' => false

        ]);
        //Date de début Event
        $builder->add('minDate', DateType::class, [
            'label' => 'Entre',
            'widget' => 'single_text',
            'required' => false
        ]);
        //Date de fin de l'Event
        $builder->add('maxDate', DateType::class, [
            'label' => 'et',
            'widget' => 'single_text',
            'required' => false
        ]);
        // Les cases a cocher
        $builder->add('organiser', CheckboxType::class, [
            'label' => 'Sorties dont je suis l\'organisateur'
            , 'required' => false
        ]);
        $builder->add('isParticipant', CheckboxType::class, [
            'label' => 'Sorties  auxquelles je suis inscrit'
            , 'required' => false
        ]);
        $builder->add('isNotParticipant', CheckboxType::class, [
            'label' => 'Sorties auxquelles je ne suis pas inscrit'
            , 'required' => false
        ]);
        $builder->add('archived', CheckboxType::class, [
            'label' => 'Sorties passées'
            , 'required' => false
        ]);

        $builder->add('submit', SubmitType::class, ['label' => 'Rechercher', 'attr' => ['class' => 'hollow button secondary']]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
        ]);
    }

}
