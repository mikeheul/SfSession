<?php

namespace App\Form;

use App\Entity\FormModule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class, [
                'label' => "Intitulé",
                'label_attr' => ['class' => 'text-light']
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label_attr' => ['class' => 'text-light']
            ])
            ->add('valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormModule::class,
        ]);
    }
}
