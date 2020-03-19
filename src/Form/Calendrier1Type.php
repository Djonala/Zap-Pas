<?php

namespace App\Form;

use App\Entity\Calendrier;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Calendrier1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('url')
            ->add('users', EntityType::class, [
                'class'=>Users::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
//                        ->where('u.roles::text LIKE :role')
                        ->orderBy('u.email', 'ASC');
//                        ->setParameter('role', 'ROLE_ADMIN');
                },

                'choice_label'=>'prenom',
                'multiple' => true,
                'expanded' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}
