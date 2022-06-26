<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\SessionType;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FormationCrudController extends AbstractCrudController
{
    // it must return a FQCN (fully-qualified class name) of a Doctrine ORM entity
    public static function getEntityFqcn(): string
    {
        return Formation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'intitule',
            'description',
            CollectionField::new('sessions')->setEntryType(SessionType::class)->allowAdd(false)->hideOnIndex()
        ];
    }
}