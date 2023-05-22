<?php

namespace App\Form;

use App\Model\TraiterData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Assert;

class TraiterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('famille', Assert\TextType::class, [
            'label_attr' => [
                'for' => 'inputName',
                'class' => 'form-label'
            ],
            'label' => 'Nom du patient',
            'attr' => [
                'id' => 'inputName',
                'class' => 'form-control'
            ],
            'required' => true,
            'constraints' => new NotNull()
        ])
        ->add('educateur', Assert\TextType::class, [
            'label_attr' => [
                'for' => 'inputName',
                'class' => 'form-label'
            ],
            'label' => "Nom de l'educateur",
            'attr' => [
                'id' => 'inputName',
                'class' => 'form-control'
            ],
            'required' => true,
            'constraints' => new NotNull()
        ])
        ->add('Submit', Assert\SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-outline-primary mt-3 mb-4',
            ],
            'label' => 'Valider'
        ])
        ->setMethod('post')
        ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TraiterData::class,
        ]);
    }
}
