<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class CourseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Course::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('subject'),
            TextEditorField::new('description'),
            AssociationField::new('lessonDocs')->setFormTypeOptions([
                'multiple' => true,
                'by_reference' => false,
            ]),
            DateField::new('date'),
            TimeField::new('time'),
            TextField::new('location'),
            AssociationField::new('users')
                ->setFormTypeOptions([
                    'multiple' => true,
                    'by_reference' => false,
                    'expanded' => true
                ])
        ];
    }

}
