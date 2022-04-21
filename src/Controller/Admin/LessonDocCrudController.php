<?php

namespace App\Controller\Admin;

use App\Entity\LessonDoc;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class LessonDocCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LessonDoc::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextareaField::new('description'),
            AssociationField::new('course'),
            TextField::new('documentFile')->setFormType(VichFileType::class)->onlyOnForms(),
        ];
    }

}
