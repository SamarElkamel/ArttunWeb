<?php

namespace App\Form;

use App\Entity\Evenement;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('date')
            ->add('frais')
            ->add('photo', FileType::class, [
                'label' => false,
                'mapped' => false, 
                'required' => false, 
            ])
            ->add('localisation')
            ->add('siteweb')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
