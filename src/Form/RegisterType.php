<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('utilisateur', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'id' => "inputId"
                ],
                'label_attr' => [
                    'for' => 'inputId',
                    'class' => 'form-label'
                ],
                'required' => true,
                'constraints' => [
                    new NotNull(message: 'Identifiant requis')
                ],
                'label' => 'Identifiant'
            ])
            ->add('nom', TextType::class, [
                'label_attr' => [
                    'for' => 'inputLastName',
                    'class' => 'form-label'
                ],
                'label' => 'Nom *',
                'attr' => [
                    'id' => 'inputLastName',
                    'class' => 'form-control'
                ],
                'required' => true,
                'constraints' => new NotNull()
            ])
            ->add('prenom', TextType::class, [
                'label_attr' => [
                    'for' => 'inputFirstName',
                    'class' => 'form-label'
                ],
                'attr' => [
                    'id' => 'inputFirstName',
                    'class' => 'form-control'
                ]
            ])
            ->add('age', IntegerType::class, [
                'label_attr' => [
                    'for' => 'inputAge',
                    'class' => 'form-label'
                ],
                'attr' => [
                    'id' => 'inputAge',
                    'class' => 'form-control'
                ],
                'label' => 'Age *',
                'required' => true,
                'constraints' => [
                    new NotNull(message: 'Age requis'),
                    new Positive(message: "L'age doit être positif")
                ]
            ])
            ->add('email', EmailType::class, [
                'label_attr' => [
                    'for' => 'inputEmail',
                    'class' => 'form-label'
                ],
                'attr' => [
                    'id' => 'inputEmail',
                    'class' => 'form-control'
                ],
                'label' => 'Email *',
                'required' => true,
                'constraints' => [
                    new NotNull(message: 'Email requis'),
                    new Email(message: 'Email invalide')
                ]
            ])
            ->add('mdp', PasswordType::class, [
                'label_attr' => [
                    'for' => 'inputMdp',
                    'class' => 'form-label'
                ],
                'attr' => [
                    'id' => 'inputMdp',
                    'class' => 'form-control'
                ],
                'label' => 'Mot de passe *',
                'required' => true,
                'constraints'=> new NotNull(message: 'Mot de passe requis')
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary mt-3',
                ],
                
            ])
            ->setMethod('post')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
