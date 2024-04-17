<?php

namespace App\Form;

use App\Entity\user\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class UserType1 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('prenom', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prenom',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Client' => 'client',
                    'Admin' => 'admin',
                ],
                'attr' => [
                    'placeholder' => 'Type',
                ],
            ])
            ->add('adresseMail', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse Mail',
                ],
            ])
            ->add('id', HiddenType::class, [
                'label' => false,
                'attr' => [
                    'id' => 'idfield',
                    'name' => 'id',
                ],
            ])
            ->add('mdp', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Password',
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Photo',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => [
                    'class' => 'btn-primary',
                ],
            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
