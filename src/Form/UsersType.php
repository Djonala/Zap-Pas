<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //on construit le formulaire
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            // on ajoute les roles avec une liste a choix
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Super-Administrateur' => 'ROLE_SUPER_ADMIN',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Stagiaire' => 'ROLE_STAGIAIRE',
                    'Intervenant' => 'ROLE_INTERVENANT',
                ],
                'multiple' => true,
            ])
            // ajout de password en non visible
            ->add('password', PasswordType::class)
            ->add('envoyer', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
