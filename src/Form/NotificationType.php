<?php

namespace App\Form;

use App\Entity\Notification;
use App\Entity\NotificationTemplate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sources',EntityType::class,[
                'class' => NotificationTemplate::class,
                'expanded' => true,
                'multiple' => true,
                'by_reference' => false,
                'attr' => ['class' => 'form-switch'],
                'row_attr' => ['class' => 'mb-3'],
                'label' => 'Benachrichtigung, wenn:'
            ])
            ->add('Speichern', SubmitType::class,[
                'attr' => ['class' => 'px-5 btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}
