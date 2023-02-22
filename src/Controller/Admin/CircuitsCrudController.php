<?php

namespace App\Controller\Admin;

use App\Entity\Circuits;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CircuitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Circuits::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
