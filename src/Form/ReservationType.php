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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          //  ->add('empruntDate')
            ->add('rendered')
            ->add(
            'email',
                TextType::class,
            array(
                'label' => 'Email',
                'attr' => array(
                    'placeholder' => 'Votre email' ,
                    'class' => 'form-control'
                )

            ))
           ->add('isRendered')
            ->add('material')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
