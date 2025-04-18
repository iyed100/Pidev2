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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez un type'
            ])
            ->add('montant', MoneyType::class, [
                'label' => 'Montant (€)',
                'currency' => 'EUR',
                'attr' => ['class' => 'form-control'],
                'scale' => 2
            ])
            ->add('conditions', TextareaType::class, [
                'label' => 'Conditions incluses',
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ])
            ->add('date_souscription', DateType::class, [
                'label' => 'Date de souscription',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control datepicker'],
                'html5' => false,
                'format' => 'dd/MM/yyyy'
            ])
            ->add('date_expiration', DateType::class, [
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control datepicker'],
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'constraints' => [
                    new Callback([$this, 'validateDates'])
                ]
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                    'En attente' => 'En attente'
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('reservation', EntityType::class, [
                'class' => Reservation::class,
                'choice_label' => 'id',
                'attr' => ['class' => 'd-none'],
                'label' => false
            ]);
    }

    public function validateDates($value, ExecutionContextInterface $context)
    {
        $form = $context->getRoot();
        $data = $form->getData();
        
        if (!($data instanceof Assurance)) {
            return;
        }

        if ($data->getDateSouscription() && $data->getDateExpiration() 
            && $data->getDateExpiration() <= $data->getDateSouscription()) {
            $context->buildViolation('La date d\'expiration doit être postérieure à la date de souscription')
                    ->atPath('date_expiration')
                    ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assurance::class,
        ]);
    }
}