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
                    'class' => "form-control"
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => "Nom",
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => "Prénom",
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('birthDate', DateType::class, [
                'label' => "Date de naissance",
                'format' => "dd-MM-yyyy",
                'years' => range(1900, 2020),
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('address1', TextType::class, [
                'label' => "Adresse",
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('address2', TextType::class, [
                'label' => "Complément d'addresse",
                'required' => false,
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('zipCode', TextType::class, [
                'label' => "Code Postal",
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => "Téléphone",
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'required' => false,
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => "Si le patient en a une"
                ]
            ])
            ->add('doctor', EntityType::class, [
                'class' => User::class,
                'group_by' => "job.title",
                'query_builder' => function (UserRepository $user) {
                    return $user->createQueryBuilder('u')
                        ->orderBy('u.job', 'ASC');
                },
                'choice_label' => 'lastName',
                'label' => "Médecin traitant",
                'attr' => [
                    'class' => "form-control"
                ]

            ])
            ->add('nurses', EntityType::class, [
                'class' => User::class,
                'group_by' => "job.title",
                'query_builder' => function (UserRepository $user) {
                    return $user->createQueryBuilder('u')
                        ->orderBy('u.job', 'ASC');
                },
                'choice_label' => 'lastName',
                'label' => "Infirmier(e)s",
                'attr' => [
                    'class' => "form-control"
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
