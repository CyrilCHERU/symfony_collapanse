<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
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
                'label' => "Nom",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => "Prénom",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('birthDate', DateType::class, [
                'label' => "Date de naissance",
                'format' => "dd-MM-yyyy",
                'years' => range(1900, 2020),
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
            ->add('phone', TextType::class, [
                'label' => "Téléphone",
                'attr' => [
                    'class' => "form-control mb-2"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'required' => false,
                'attr' => [
                    'class' => "form-control mb-2",
                    'placeholder' => "Si le patient en a une"
                ]
            ])
            ->add('doctor', EntityType::class, [
                'class' => User::class,

                'query_builder' => function (UserRepository $user) {
                    return $user->createQueryBuilder('u')
                        ->where("j.title = 'Docteur'")
                        ->join("u.job", "j");
                },
                'choice_label' => function (User $user) {
                    return $user->getFirstName() . " " . $user->getLastName();
                },
                'label' => "Médecin traitant",
                'attr' => [
                    'class' => "form-control mb-3"
                ]

            ])
            ->add('nurses', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $user) {
                    return $user->createQueryBuilder('u')
                        ->where("j.title = 'Infirmier(e)'")
                        ->join("u.job", "j");
                },
                'choice_label' => 'lastName',
                'multiple' => true,
                'label' => "Infirmier(e)s",
                'attr' => [
                    'class' => "form-control mt-2 js-select"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
