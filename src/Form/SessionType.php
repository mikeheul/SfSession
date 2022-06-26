<?php

namespace App\Form;

use App\Entity\Formateur;
use App\Entity\Formation;
use App\Entity\Session;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class, [
                'label' => 'Intitulé de session'
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text'
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text'
            ])
            ->add('nbPlaces', NumberType::class, [
                'label' => 'Nombre de places',
                'html5' => true,
                'attr' => ['class' => 'number']
            ])
            ->add('formateurReferent', EntityType::class, [
                'label' => 'Formateur référent',
                'class' => Formateur::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                            ->orderBy('f.nom', 'ASC');
                }
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'intitule'
            ])
            ->add('valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-dark']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
