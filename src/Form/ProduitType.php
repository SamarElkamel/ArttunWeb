<?php
namespace App\Form;

use App\Entity\Produit;
use App\Entity\CategorieProduit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex; // Import Regex constraint
use Symfony\Component\Form\Extension\Core\Type\FileType;



use Symfony\Component\Validator\Constraints\File;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('prix', TextType::class, [
                'attr' => ['placeholder' => 'Enter price'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Please enter a valid number.',
                    ]),
                ],
            ])
            ->add('categorieProduit', EntityType::class, [
                'class' => CategorieProduit::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Select a category',
                'required' => true,
            ])
            ->add('image', FileType::class, [
                'label' => 'image',
                'mapped' => false, 
                'required' => true, 
              
                ],
            );
            
            }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
