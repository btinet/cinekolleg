<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class)
            ->add('email',EmailType::class)
            ->add('hasNewsletter',ChoiceType::class,[
                'multiple' => false,
                'expanded' => true,
                'choices' => array(
                    'abonnieren' => '1',
                    'nicht abonnieren' => '0'
                ),
                'label' => 'Newsletter',
                'required' => true,
                'attr' => ['class' => 'form-switch'],
            ])
            ->add('Speichern', SubmitType::class,[
                'attr' => ['class' => 'px-5 btn-primary']
            ])
        ;

        $builder->get('hasNewsletter')
            ->addModelTransformer(new CallbackTransformer(
                function ($property) {
                    return (string) $property;
                },
                function ($property) {
                    return (bool) $property;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
