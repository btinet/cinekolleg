<?php

namespace App\Controller\Admin;

use App\Entity\CourseImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CourseImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseImage::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            AssociationField::new('course'),
            AssociationField::new('user'),
            TextField::new('imageFile')->setFormType(VichImageType::class)->onlyOnForms(),
        ];
    }

}
