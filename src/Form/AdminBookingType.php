<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date de début"
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date de fin"
            ])
            ->add('comment', TextType::class, [
                'label' => "Commentaire"
            ])
            ->add('booker', EntityType::class, [
                'class' => User::class,
                'label' => 'Auteur',
                'choice_label' => function($user) {
                    return $user->getFirstName() . " " . strtoupper($user->getLastName());
                } // ou 'choice_label' => 'fullName'
            ])
            ->add('ad', EntityType::class, [
                'class' => Ad::class,
                'choice_label' => 'title',
                'label' => 'Annonce'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
