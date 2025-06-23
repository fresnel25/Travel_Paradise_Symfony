<?php

namespace App\Form;

use App\Entity\Associer;
use App\Entity\Visit;
use App\Entity\Visiteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssocierForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('visite', EntityType::class, [
                'class' => Visit::class,
                'choice_label' => 'nom', 
                'label' => 'Visite concernée',
                'placeholder' => 'Sélectionner une visite'
            ])
            ->add('visiteur', EntityType::class, [
                'class' => Visiteur::class,
                'choice_label' => 'nom', 
                'label' => 'Visiteur',
                'placeholder' => 'Sélectionner un visiteur'
            ])
            ->add('presence', CheckboxType::class, [
                'required' => false,
                'label' => 'Présent ?'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Associer::class,
        ]);
    }
}
