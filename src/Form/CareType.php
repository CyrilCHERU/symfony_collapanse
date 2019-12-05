<?php

namespace App\Form;

use App\Entity\Care;
use App\Entity\Patient;
use App\Repository\PatientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\User\UserInterface;

class CareType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt', DateType::class, [
                'label' => "Ouverture du soin le",
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('endedAt', DateType::class, [
                'label' => "Fermeture du soin le",
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ne pas remplir'
                ]
            ])
            ->add('woundType', TextType::class, [
                'label' => "Description de la plaie",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => function (Patient $patient) {
                    return $patient->getFullName();
                },
                'label' => "Patient",
                'attr' => [
                    'class' => "form-control mt-2 js-select"
                ]
            ]);
        // ->add('patient', TextType::class, [
        //     'label' => "Patient",
        //     'attr' => [
        //         'class' => "form-control"
        //     ]
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Care::class,
        ]);
    }
}
