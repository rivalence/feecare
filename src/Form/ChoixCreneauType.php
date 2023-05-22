<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Assert;

class ChoixCreneauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', Assert\HiddenType::class)
            ->add('submit', Assert\SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary',
                ],
                'label' => 'Choisir ce créneau'
            ])
            ->setMethod("POST")
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChoixCreneauData::class
        ]);
    }
}
