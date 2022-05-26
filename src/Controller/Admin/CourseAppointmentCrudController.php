<?php

namespace App\Controller\Admin;

use App\Entity\CourseAppointment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class CourseAppointmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseAppointment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('subject'),
            DateField::new('date'),
            TimeField::new('time'),
            TextField::new('location'),
            AssociationField::new('course')
        ];
    }

}
