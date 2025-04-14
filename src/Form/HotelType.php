<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Hotel Name',
                'attr' => [
                    'placeholder' => 'Enter hotel name',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a hotel name'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Hotel name should be at least {{ limit }} characters long'
                    ])
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Address',
                'attr' => [
                    'placeholder' => 'Enter hotel address',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an address'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Address should be at least {{ limit }} characters long'
                    ])
                ]
            ])
            ->add('nombreEtoiles', IntegerType::class, [
                'label' => 'Number of Stars',
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the number of stars'
                    ]),
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'The number of stars must be between {{ min }} and {{ max }}'
                    ])
                ]
            ])
            ->add('prixParNuit', MoneyType::class, [
                'label' => 'Price per Night',
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter price per night'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a price per night'
                    ]),
                    new Positive([
                        'message' => 'Price must be greater than zero'
                    ])
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Hotel Image',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/*'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG, GIF)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
} 