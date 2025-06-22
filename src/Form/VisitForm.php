<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Visit;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pictures', FileType::class, [
                'label' => 'Photos de la visite',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
            ])
            ->add('country')
            ->add('adress')
            ->add('date')
            ->add('start_time')
            ->add('end_time')
            ->add('duration')
            ->add('comment')
            ->add('guide', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getFirstName() . ' ' . $user->getLastName();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_GUIDE%');
                },
                'required' => true,
                'placeholder' => 'Sélectionnez un guide',
                'label' => 'Guide Touristique',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visit::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'visit_item',
        ]);
    }
}
