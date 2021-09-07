<?php

namespace App\Form;

use App\Entity\Student;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class StudentType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un champs !
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfigurations($label, $placeholder){
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'matricule', 
                TextType::class, 
                $this->getConfigurations('Matricule', 'MAT0045')
            )
            ->add(
                'prenom', 
                TextType::class, 
                $this->getConfigurations('Prénom', 'Prénom de l\'étudiant')
            )
            ->add(
                'nom', 
                TextType::class, 
                $this->getConfigurations('Nom', 'Nom de l\'étudiant')
            )
            ->add(
                'age', 
                IntegerType::class, 
                $this->getConfigurations('Age', 'Age de l\'étudiant')
            )->add(
                'images', 
                CollectionType::class, 
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
                
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}