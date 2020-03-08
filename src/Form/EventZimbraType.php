<?php

namespace App\Form;

use App\Entity\EventZimbra;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventZimbraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matiere')
            ->add('nomFormateur')
            ->add('lieu')
            ->add('emailIntervenant')
            ->add('dateDebutEvent')
            ->add('dateFinEvent')
            ->add('calendrier')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventZimbra::class,
        ]);
    }
}
