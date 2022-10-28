<?php

namespace App\Form;

use App\Entity\Material;
use App\Entity\Reservation;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          //  ->add('empruntDate')
            ->add(
            'rendered',
            DateTimeType::class,
                array(
                'widget' => 'single_text',
                'label' => "Date à rendre",
                'attr' => array(
                    'class' => 'mb-3'   
                   )
                )
            )        
         
            
            ->add(
            'email',
                TextType::class,
                array(
                'label' => 'Email',
                'attr' => array(
                    'placeholder' => 'Votre email' ,
                    'class' => 'form-control mb-3'
                   )

                )
            )
         

            ->add(
            'material',
                EntityType::class,
                array(
                'class' => Material::class,
                'label' => "Matériel",
                'attr' => array(
                    'class' => 'form-control mb-3'
                    )
                )
            )
  

            ->add(
                'isRendered',
                CheckboxType::class,
                array(
                    'label' => "Rendu",
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-check-input mb-3'
                    )
                )
            )

        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
