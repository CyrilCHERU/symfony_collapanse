<?php

namespace App\Form;

use App\Entity\Care;
use App\Entity\Intervention;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => "Date",
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('comment', TextareaType::class, [
                'label' => "Votre commentaire",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('care', EntityType::class, [
                'class' => Care::class,
                'choice_label' => 'woundType',
                'label' => "Soin concerné",
                'attr' => [
                    'row' => '10'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}
