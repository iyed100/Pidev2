<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class HotelType extends AbstractType
{
   

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('nombreEtoiles')
            ->add('prixParNuit')
            ->add('image', FileType::class, [
                'label' => 'Image de l\'hôtel',
                'required' => false,
                'mapped' => false, // Important pour les uploads de fichiers
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}
