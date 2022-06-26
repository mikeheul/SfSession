<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Form\FormModuleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class, [
                'label' => 'Intitulé',
                'label_attr' => ['class' => 'text-light']
            ])
            ->add('formModules', CollectionType::class, [
                'label' => " ",
                'entry_type' => FormModuleType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-dark mt-3']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
