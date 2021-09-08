<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getOperation('Ancien Password', 'Mettez votre ancien password'))
            ->add('newPassword', PasswordType::class, $this->getOperation('Nouveau Password', 'Mettez votre nouveau password'))
            ->add('confirmPassword', PasswordType::class, $this->getOperation('Confirm Password', 'Confirm le nouveau password'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}