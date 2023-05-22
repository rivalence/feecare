<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('legend', Assert\TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'legende'
                ],
                'label_attr' => [
                    'class' => 'form-label',
                    'for' => 'legende'
                ],
                'label' => 'Entrez une légende'
            ])
            ->add('dataPost', Assert\FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'dataPost'
                ],
                'label_attr' => [
                    'class' => 'form-label',
                    'for' => 'dataPost'
                ],
                'label' => 'Photo',
                'mapped' => 'false'
            ])
            ->add('submit', Assert\SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary mt-4'
                ],
                'label' => 'Publier'
            ])
            ->setMethod('post')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostData::class,
        ]);
    }
}
