<?php

namespace App\Controller\Admin;

use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\FormModuleType;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProgrammeCrudController extends AbstractCrudController
{
    // it must return a FQCN (fully-qualified class name) of a Doctrine ORM entity
    public static function getEntityFqcn(): string
    {
        return Programme::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            CollectionField::new('formModule')->setEntryType(FormModuleType::class)->allowAdd(false),
            CollectionField::new('session')->setEntryType(SessionType::class)->allowAdd(false),
            'nbJours',
        ];
    }
}