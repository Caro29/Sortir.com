<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label'=>'Identifiant'])
            ->add('lastName', TextType::class, ['label'=>'Nom'])
            ->add('firstName', TextType::class, ['label'=>'Prénom'])
            ->add('phone', TextType::class, ['label'=>'Téléphone', 'attr' =>['placeholder' => '33_  _ _  _ _  _ _  _ _']])
            ->add('email', EmailType::class, ['label'=>'Adresse email'])
            ->add('plainPassword', PasswordType::class, [

                'label' => 'Mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Mot de passe obligatoire',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire minimum 6 caractères',
                        // Taille du mot de passe max
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('campus', EntityType::class, [
                'query_builder' => function (CampusRepository $campusRepository) {
                    return $campusRepository->getCampusQueryBuilder();
                },
                'choice_label' => 'name',
                'class' => Campus::class,
            ])
            ->add('active', CheckboxType::class, ['label' => 'Utilisateur actif', 'attr' => ['checked' => true], 'required' => false])
            ->add('Valider', SubmitType::class, ['attr' => ['class' => 'hollow button secondary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
