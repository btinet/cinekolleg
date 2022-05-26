<?php

namespace App\Controller\Admin;

use App\Entity\CourseSection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class CourseSectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseSection::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextareaField::new('description')
                ->setTemplatePath('@EasyAdmin/crud/field/text_editor.html.twig')
                ->setFormType(CKEditorType::class)->setFormTypeOptions([
                    'base_path' => 'build/ckeditor',
                    'js_path' => 'build/ckeditor/ckeditor.js',
                    'config' => [
                        'toolbar' => 'full',
                        'styles' => [
                            'name' => 'images',
                            'element' => 'img',
                            'attributes' => ['class' =>  'img-fluid']
                        ]
                        ]
                ])
            ->addCssClass('field-ck-editor')
            ,
            AssociationField::new('course')
        ];
    }

}
