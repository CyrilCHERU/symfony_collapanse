<?php

namespace App\Form;

use App\Entity\User;
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
                    'class' => "form-control"
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => "Votre nom",
                'attr' => [
                    'class' => "from-control"
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => "Votre prénom",
                'attr' => [
                    'class' => "from-control"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Votre email (Vous servira d'identifiant de connexion)",
                'attr' => [
                    'class' => "from-control"
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe ne correspondent pas :(",
                'first_options' => [
                    'label' => "Votre mot de passe"
                ],
                'second_options' => [
                    'label' => "Veuillez resaisir votre mot de passe"
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => "Téléphone",
                'attr' => [
                    'class' => "from-control"
                ]
            ])
            ->add('adeli', TextType::class, [
                'label' => "Votre N° ADELI",
                'attr' => [
                    'class' => "from-control"
                ]
            ])
            ->add('address1', TextType::class, [
                'label' => "Adresse",
                'attr' => [
                    'class' => "from-control"
                ]
            ])
            ->add('address2', TextType::class, [
                'label' => "Complément d'addresse",
                'required' => false,
                'attr' => [
                    'class' => "from-control"
                ]
            ])
            ->add('zipCode', TextType::class, [
                'label' => "Code Postal",
                'attr' => [
                    'class' => "from-control"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'class' => "from-control"
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
