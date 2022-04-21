<?php

namespace App\Controller\Admin;

use App\Entity\CourseComment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CourseCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseComment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user'),
            AssociationField::new('course'),
            TextField::new('content'),
        ];
    }

}
