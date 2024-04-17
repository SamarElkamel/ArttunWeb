<?php

namespace App\Form\user;

use App\Entity\user\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom',
                    'id' => 'first_name'
                ],
                'label' => false,
                'row_attr' => ['class' => 'row'],
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Prenom',
                    'id' => 'last_name'
                ],
                'label' => false,
                'row_attr' => ['class' => 'row'],
            ])
            ->add('adresseMail', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email',
                    'id' => 'email'
                ],
                'label' => false,
                'row_attr' => ['class' => 'row'],
            ])
            ->add('mdp', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'Password',
                    'id' => 'password'
                ],
                'label' => false,
                'row_attr' => ['class' => 'row'],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'Confirm Password',
                ],
                'label' => false,
                'row_attr' => ['class' => 'row'],
            ])
            ->add('photo', FileType::class, [
                'label' => false,
                'row_attr' => ['class' => 'row'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sign Up',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
