<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Assert;
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
            ->add('utilisateur', Assert\TextType::class, [
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
            ->add('nom', Assert\TextType::class, [
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
            ->add('prenom', Assert\TextType::class, [
                'label_attr' => [
                    'for' => 'inputFirstName',
                    'class' => 'form-label'
                ],
                'attr' => [
                    'id' => 'inputFirstName',
                    'class' => 'form-control'
                ]
            ])
            ->add('age', Assert\IntegerType::class, [
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
                    new Positive(message: "L'age ne peut pas être négatif")
                ]
            ])
            ->add('email', Assert\EmailType::class, [
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
            ->add('mdp', Assert\RepeatedType::class, [
                'type' => Assert\PasswordType::class,
                'invalid_message' => 'Les mots de passes rentrés doivent être identique',
                'label_attr' => [
                    'for' => 'inputMdp',
                    'class' => 'form-label'
                ],
                'options' => [
                    'attr' => [
                        'id' => 'inputMdp',
                        'class' => 'form-control'
                        ]
                    ],
                'first_options' => ['label' => 'Mot de passe*'],
                'second_options' => ['label' => 'Retapez le mot de passe*'],
                'required' => true,
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
            'data_class' => RegisterData::class,
        ]);
    }
}
