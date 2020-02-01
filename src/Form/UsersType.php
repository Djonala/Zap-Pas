<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'SuperAdmin' => 'ROLE_SUPER_ADMIN',
                    'Admin' => 'ROLE_ADMIN',
                    'stagiaire' => 'ROLE_STAGIAIRE',
                    'formateur' => 'ROLE_FORMATEUR',
                    'Administratif' => 'ROLE_ADMINISTRATIF'
                ],
                'multiple' => false,
            ])
            ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
