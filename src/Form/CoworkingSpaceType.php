<?php

namespace App\Form;

use App\Entity\CoworkingSpace;
use App\Entity\Hotel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoworkingSpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Name',
                'attr' => ['placeholder' => 'Enter space name']
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Address',
                'attr' => ['placeholder' => 'Enter space address']
            ])
            ->add('prixParHeure', MoneyType::class, [
                'label' => 'Price per Hour',
                'currency' => 'USD',
                'divisor' => 100
            ])
            ->add('image', UrlType::class, [
                'label' => 'Image URL',
                'required' => false,
                'attr' => ['placeholder' => 'Enter image URL']
            ])
            ->add('hotel', EntityType::class, [
                'class' => Hotel::class,
                'choice_label' => 'nom',
                'label' => 'Associated Hotel',
                'required' => true,
                'placeholder' => 'Select a hotel'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoworkingSpace::class,
        ]);
    }
} 