<?php

namespace App\Form;

use App\Model\CommentaireData;
use PHPUnit\Framework\Assert as FrameworkAssert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', Assert\TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre commentaire...',
                    'aria-describedby' => 'ariaComment',
                    'id' => 'comment'
                ],
                'label' => false
            ])
            ->add('postId', Assert\HiddenType::class)
            ->setMethod('post')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentaireData::class,
        ]);
    }
}
