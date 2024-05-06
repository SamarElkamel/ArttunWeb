<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\TypeReclamation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\TypeReclamationRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    private $typeReclamationRepository;

    public function __construct(TypeReclamationRepository $typeReclamationRepository)
    {
        $this->typeReclamationRepository = $typeReclamationRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Fetch TypeReclamation entities
        $typeReclamations = $this->typeReclamationRepository->findAll();

        // Initialize the counter
        $counter = 1;
        
        // Map Libelle to incremental values
        $choices = [];
        foreach ($typeReclamations as $typeReclamation) {
            $choices[$typeReclamation->getLibelle()] = $counter;
            $counter++;
        }

        $builder
            ->add('titre')
            ->add('description')
            ->add('reply')
            ->add('idClient')
            ->add('idType', ChoiceType::class, [
                'choices' => $choices,
                'label' => 'Type de Réclamation',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('idCommande')
            // Add an event listener for form submission
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
                // Reset the counter to 0 after form submission
                $options['counter_reset']();
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
            // Add a custom option to pass the counter reset callback
            'counter_reset' => static function () use (&$counter) {
                $counter = 1;
            },
        ]);
    }
}