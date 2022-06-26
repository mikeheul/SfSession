<?php

namespace App\Controller\Admin;

use App\Entity\Stagiaire;
use App\Form\SessionType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class StagiaireCrudController extends AbstractCrudController
{
    // it must return a FQCN (fully-qualified class name) of a Doctrine ORM entity
    public static function getEntityFqcn(): string
    {
        return Stagiaire::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'nom',
            'prenom',
            'sexe',
            'dateNaissance',
            'adresse',
            'codePostal',
            'ville',
            CollectionField::new('sessions')->setEntryType(SessionType::class)->allowAdd(false)->hideOnIndex()
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setDefaultSort(['nom' => 'ASC'])
        ->setPaginatorPageSize(4);
    }

    // ...
}