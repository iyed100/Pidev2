<?php

namespace App\Form;

use App\Entity\CoworkingSpace;
use App\Entity\Hotel;
use App\Entity\Reservation;
use App\Entity\TransportMean;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbrnuit')
            ->add('nbrheure')
            ->add('typeservice')
            ->add('statut')
            ->add('hotel', EntityType::class, [
                'class' => Hotel::class,
                'choice_label' => 'id',
            ])
            ->add('coworkingSpace', EntityType::class, [
                'class' => CoworkingSpace::class,
                'choice_label' => 'id',
            ])
            ->add('transportMean', EntityType::class, [
                'class' => TransportMean::class,
                'choice_label' => 'id',
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
