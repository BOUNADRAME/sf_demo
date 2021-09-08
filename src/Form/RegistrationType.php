<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getOperation('Nom', 'Votre nom de famille...'))
            ->add('lastName', TextType::class, $this->getOperation('Prénom', 'Votre prénom...'))
            ->add('email', EmailType::class, $this->getOperation('Email', 'Votre adresse email'))
            ->add('picture', UrlType::class, $this->getOperation('Photo de profil', 'URL de votre avatar...'))
            ->add('introduction', TextType::class, $this->getOperation('Introduction', 'Présentez-vous'))
            ->add('description', TextareaType::class, $this->getOperation('Description détaillée', 'Présentez vous en détail'))
            ->add('password', PasswordType::class, $this->getOperation('Password', 'Mettre un bon mot de passe'))
            ->add('passwordConfirm', PasswordType::class, $this->getOperation('Confirm Password', 'Confirm mot de passe'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}