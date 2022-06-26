<?php

namespace App\Controller\Admin;

use App\Entity\Session;
use App\Form\StagiaireType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SessionCrudController extends AbstractCrudController
{
    // it must return a FQCN (fully-qualified class name) of a Doctrine ORM entity
    public static function getEntityFqcn(): string
    {
        return Session::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'intitule',
            'dateDebut',
            'dateFin',
            'nbPlaces',
            CollectionField::new('stagiaires')->setEntryType(StagiaireType::class)->allowAdd(false)->hideOnIndex()
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setDefaultSort(['dateDebut' => 'ASC'])
        ->setPaginatorPageSize(4);
    }
}