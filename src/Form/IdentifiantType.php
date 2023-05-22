<?php

namespace App\Form;

use App\Entity\Identifiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class IdentifiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'id' => "inputId"
                ],
                'label_attr' => [
                    'for' => 'inputId',
                    'class' => 'form-label'
                ],
                'label' => 'Identifiant',
                'required' => true,
                'constraints' => [
                    new NotNull(message: 'Identifiant requis'),
                    new NotBlank(message: 'Identifiant requis')
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary',
                    'name' => 'valider'
                ]
            ])
            ->setMethod('post')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IdentifiantData::class,
        ]);
    }
}
