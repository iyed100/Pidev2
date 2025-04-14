<?php

namespace App\Form;

use App\Entity\Route;
use App\Entity\TransportMeans;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('depart', TextType::class, [
                'label' => 'Departure',
                'attr' => ['class' => 'form-control']
            ])
            ->add('arrivee', TextType::class, [
                'label' => 'Arrival',
                'attr' => ['class' => 'form-control']
            ])
            ->add('distance', NumberType::class, [
                'label' => 'Distance (km)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('duree', TextType::class, [
                'label' => 'Duration (HH:MM)',
                'attr' => ['class' => 'form-control', 'placeholder' => 'e.g. 02:30']
            ])
            ->add('transport', EntityType::class, [
                'class' => TransportMeans::class,
                'choice_label' => 'nom',
                'label' => 'Transport Mean',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Route::class,
        ]);
    }
} 