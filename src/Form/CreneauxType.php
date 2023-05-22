<?php

namespace App\Form;

use App\Entity\Creneaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreneauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateCreneau', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime', 
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'dateCreneau'
                ],
                'label_attr' => [
                    'for' => 'dateCreneau',
                    'class' => 'form-label'
                ],
                'label' => 'Date'
            ])
            ->add('timeCreneau', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'label' => 'Heure',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'timeCreneau'
                ],
                'label_attr' => [
                    'for' => 'timeCreneau',
                    'class' => 'form-label'
                ],

            ])
            ->add('Submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary mt-3',
                ],
                'label' => 'Ajouter une disponibilité'
            ])
            ->setMethod('post')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreneauxData::class,
        ]);
    }
}
