<?php

namespace App\Form;

use App\Entity\CourseImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CourseImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile',VichImageType::class,[
                'label' => 'Bildbeitrag hochladen',
                'attr' => [
                    'accept' => "image/*"
                ],
                'constraints' => [
                    new Image()
                ]
            ])
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CourseImage::class,
        ]);
    }
}
