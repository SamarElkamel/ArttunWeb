<?php

namespace App\Form\user;

use App\Entity\user\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => ['placeholder' => 'Placeholder'],
                'label' => 'First Name',
                'row_attr' => ['class' => 'row'],
                'label_attr' => ['class' => 'active'],
               
            ])
            ->add('prenom', null, [
                'label' => 'Last Name',
                'row_attr' => ['class' => 'row'],
                'label_attr' => ['class' => 'active'],
                'attr' => ['id' => 'last_name']
            ])
            ->add('type', null, [
                'disabled' => true,
                'label' => 'Disabled',
                'row_attr' => ['class' => 'row'],
                'label_attr' => ['class' => 'active'],
                'attr' => ['id' => 'disabled']
            ])
            ->add('mdp', null, [
                'label' => 'Password',
                'row_attr' => ['class' => 'row'],
                'label_attr' => ['class' => 'active'],
                'attr' => ['id' => 'password']
            ])
            ->add('adresseMail', null, [
                'label' => 'Email',
                'row_attr' => ['class' => 'row'],
                'label_attr' => ['class' => 'active'],
                'attr' => ['id' => 'email']
            ])
            ->add('photo', null, [
                'label' => 'Photo',
                'row_attr' => ['class' => 'row'],
                'label_attr' => ['class' => 'active'],
                'attr' => ['id' => 'photo']
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
