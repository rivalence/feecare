<?php

namespace App\Form;

use App\Model\CreneauxData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreneauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateCreneau', Assert\DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime', 
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'for' => 'dateCreneau',
                    'class' => 'form-label'
                ],
                'label' => 'Date'
            ])
            ->add('timeCreneau', Assert\TimeType::class, [
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
            ->add('type', Assert\ChoiceType::class, [
                'choices' => [
                    'Journalier' => 'Jour',
                    'Hebdomadaire' => 'Semaine'
                ],
                'empty_data' => 'Semaine',
                'attr' => [
                    'class' => 'form-select',
                    'id' => 'type-dispo',
                ],
                'label' => 'Type de disponibilté'
            ])
            ->add('recurrence', Assert\ChoiceType::class, [
                'choices' => [
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => 'Nombre de semaines'
            ])
            ->add('Submit', Assert\SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary mt-3',
                ],
                'label' => 'Ajouter une disponibilité'
            ])
            ->setMethod('POST')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreneauxData::class,
        ]);
    }
}
