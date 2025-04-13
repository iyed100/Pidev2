<?php

namespace App\Form;

use App\Entity\Assurance;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type d\'assurance',
                'choices' => [
                    'Annulation' => 'Annulation',
                    'Médicale' => 'Medicale',
                    'Bagages' => 'Bagages',
                    'Rapatriement' => 'Rapatriement',
                    'Responsabilité civile' => 'ResponsabiliteCivile',
                    'Retard/Vol' => 'RetardVol',
                    'Multirisque' => 'Multirisque',
                    'Activités sportives' => 'ActivitesSportives',
                    'Accidents personnels' => 'AccidentsPersonnels',
                    'Pertes/Documents' => 'PertesDocuments'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Sélectionnez un type'
            ])
            ->add('montant', MoneyType::class, [
                'label' => 'Montant (€)',
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '350.50'
                ],
                'scale' => 2
            ])
            ->add('conditions', TextareaType::class, [
                'label' => 'Conditions incluses',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Annulation gratuite jusqu\'à 7 jours avant le départ...'
                ]
            ])
            ->add('date_souscription', DateType::class, [
                'label' => 'Date de souscription',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control datepicker'
                ],
                'html5' => false,
                'format' => 'dd/MM/yyyy'
            ])
            ->add('date_expiration', DateType::class, [
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control datepicker'
                ],
                'html5' => false,
                'format' => 'dd/MM/yyyy'
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                    'En attente' => 'En attente'
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('reservation', EntityType::class, [
                'class' => Reservation::class,
                'choice_label' => 'id',
                'attr' => [
                    'class' => 'd-none' // Cache le champ mais le garde dans le formulaire
                ],
                'label' => false
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assurance::class,
        ]);
    }
}