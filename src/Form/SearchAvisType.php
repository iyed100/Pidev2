<?php
// src/Form/SearchAvisType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchAvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Search...',
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('search', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Search'
            ]);
    }
}