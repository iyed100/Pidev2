<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Hotel;
use App\Entity\CoworkingSpace;
use App\Entity\TransportMean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('nbrnuit', IntegerType::class, [
            'label' => 'Nombre de nuits',
            'attr' => [
                'min' => 1,
                'required' => true,
                'class' => 'form-control'
            ],
            'required' => $options['require_nights']
        ])
        ->add('nbrheure', IntegerType::class, [
            'label' => 'Nombre d\'heures',
            'required' => true,
            'attr' => [
                'min' => 1,
                'class' => 'form-control'
            ],
            'required' => $options['require_hours']
        ])
        ->add('statut', ChoiceType::class, [
            'choices' => [
                'Confirmé' => 'confirmé',
                'En attente' => 'en attente'
            ],
            'required' => true,
            'label' => 'Statut de la réservation'

        ])
        ->add('typeservice', ChoiceType::class, [
            'required' => true,
            'label' => 'Type de service',
            'choices' => [
                'Hôtel' => 'Hôtel',
                'Coworking' => 'Coworking'
            ],
            'attr' => ['class' => 'form-select'],
            'disabled' => $options['preselected_service'] !== null
        ]);

    // Champ Hôtel seulement si nécessaire
    if ($options['preselected_service'] !== 'Coworking') {
        $builder->add('hotel', EntityType::class, [
            'class' => Hotel::class,
            'choice_label' => 'nom',
            'label' => 'Hôtel',
            'attr' => ['class' => 'form-select'],
            'disabled' => $options['preselected_hotel'] !== null,
            'required' => false
        ]);
    }

    // Champ Coworking seulement si nécessaire
    if ($options['preselected_service'] !== 'Hôtel') {
        $builder->add('coworkingSpace', EntityType::class, [
            'class' => CoworkingSpace::class,
            'choice_label' => 'nom',
            'label' => 'Espace de coworking',
            'attr' => ['class' => 'form-select'],
            'disabled' => $options['preselected_coworking'] !== null,
            'required' => false
        ]);
    }

    // Champ Transport (toujours optionnel)
    $builder->add('transportMean', EntityType::class, [
        'class' => TransportMean::class,
        'choice_label' => function(TransportMean $transport) {
            return $transport->getNom() . ' (' . $transport->getType() . ')';
        },
        'label' => 'Transport',
        'attr' => ['class' => 'form-select'],
        'disabled' => $options['preselected_transport'] !== null,
        'required' => false
    ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'preselected_hotel' => null,
            'preselected_coworking' => null,
            'preselected_transport' => null,
            'preselected_service' => null,
            'require_nights' => true,
            'require_hours' => false
        ]);
    }
}