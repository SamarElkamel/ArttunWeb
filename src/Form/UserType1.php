<?php

namespace App\Form;

use App\Entity\user\User;
use App\Repository\user\AdresseRepostiory;
use Doctrine\DBAL\Types\TextType;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\File;

class UserType1 extends AbstractType
{
    private $addressChoices=[];
    public function __construct(AdresseRepostiory $adresseRepository)
    {
        $adresses = $adresseRepository->findAll();
        $this->addressChoices = [];
        foreach ($adresses as $address) {
            $name = (String)$address;
            $id=$address->getId();
            $this->addressChoices["$id"] = $name; // Assuming 'name' is the property representing the address name
        }
        //dd($this->addressChoices);
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', null, [
                'hidden' => true,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
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
                    'style' =>'margin-left: 26px;'
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
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
            ],
            ])
            ->add('adresse', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Address',
                ],
                'choices' => array_map('strval', array_flip($this->addressChoices)),
            ])
            ->add('captcha', CaptchaType::class,['label'=>false,'attr'=>['placeholder'=>'Enter The Code']])

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
