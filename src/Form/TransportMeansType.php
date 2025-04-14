<?php

namespace App\Form;

use App\Entity\TransportMeans;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class TransportMeansType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Transport Name',
                'attr' => [
                    'placeholder' => 'Enter transport name',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a transport name'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Transport name should be at least {{ limit }} characters long'
                    ])
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Transport Type',
                'choices' => [
                    'Bus' => 'bus',
                    'Train' => 'train',
                    'Metro' => 'metro',
                    'Taxi' => 'taxi',
                    'Bicycle' => 'bicycle'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a transport type'
                    ])
                ]
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacity',
                'attr' => [
                    'min' => 1,
                    'placeholder' => 'Enter capacity',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the capacity'
                    ]),
                    new Positive([
                        'message' => 'Capacity must be greater than zero'
                    ])
                ]
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Price',
                'currency' => 'EUR',
                'attr' => [
                    'placeholder' => 'Enter price',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a price'
                    ]),
                    new Positive([
                        'message' => 'Price must be greater than zero'
                    ])
                ]
            ])
            ->add('dateDepart', DateType::class, [
                'label' => 'Departure Date',
                'widget' => 'single_text',
                'html5' => false,
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'form-control js-datepicker',
                    'placeholder' => 'dd/mm/yyyy',
                    'autocomplete' => 'off'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a departure date'
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'The departure date must be today or in the future'
                    ])
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Transport Image',
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
            'data_class' => TransportMeans::class,
        ]);
    }
} 