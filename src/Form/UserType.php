<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    '-- Choississez --' => null,
                    'Femme' => "Femme",
                    'Homme' => "Homme"
                ],
                'label' => "Sexe",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => "Votre nom",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => "Votre prénom",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Votre email (Vous servira d'identifiant de connexion)",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe ne correspondent pas :(",
                'first_options' => [
                    'label' => "Votre mot de passe",
                    'attr' => [
                        'class' => "form-control mb-2"
                    ]
                ],
                'second_options' => [
                    'label' => "Veuillez resaisir votre mot de passe",
                    'attr' => [
                        'class' => "form-control mb-2"
                    ]
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => "Téléphone",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('adeli', TextType::class, [
                'label' => "Votre N° ADELI",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('address1', TextType::class, [
                'label' => "Adresse",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('address2', TextType::class, [
                'label' => "Complément d'addresse",
                'required' => false,
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('zipCode', TextType::class, [
                'label' => "Code Postal",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('job', EntityType::class, [
                'class' => Job::class,
                'label' => 'Profession',
                'choice_label' => 'title',
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
