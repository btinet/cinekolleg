<?php

namespace App\Controller\Admin;

use App\Entity\NotificationTemplate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NotificationTemplateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NotificationTemplate::class;
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
