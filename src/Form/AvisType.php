<?php
namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serviceId', null, [
                'label' => 'Service ID',
                'required' => true
            ])
            ->add('note', ChoiceType::class, [
                'choices' => [
                    '1 Star' => 1,
                    '2 Stars' => 2,
                    '3 Stars' => 3, 
                    '4 Stars' => 4,
                    '5 Stars' => 5
                ],
                'expanded' => false, // Changed from true
                'multiple' => false,
                'label' => 'Rating',
                'required' => true
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Your Review',
                'attr' => ['rows' => 5],
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}