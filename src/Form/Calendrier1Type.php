<?php

namespace App\Form;

use App\Entity\Calendrier;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Calendrier1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // on construit le formulaire
        $builder
            //on ajoute le champ pou le nom du calendrier
            ->add('nom')
            //le champs pour l'url du calendrier zimbra
            ->add('url')
            // on récupère les utilisateurs enregistrés et on les affiche avec le nom et le prénom
            ->add('users', EntityType::class, [
                'class'=>Users::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
//                        ->where('u.roles::text LIKE :role')
                        ->orderBy('u.nom', 'ASC');
//                        ->setParameter('role', 'ROLE_ADMIN');

                },

                'choice_label'=> 'nomAndPrenom',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('envoyer', SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }

}
