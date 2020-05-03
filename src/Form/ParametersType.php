<?php

namespace App\Form;

use App\Entity\UserParameters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParametersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('autorizedSendMail', CheckboxType::class, [
            'label'    => 'Autorisation envoi de mail de notification',
            'required' => false,
             'label_attr' => ['class' => 'switch-custom']
        ])
            ->add('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserParameters::class,

        ]);
    }
}
