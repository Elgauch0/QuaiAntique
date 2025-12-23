<?php

namespace App\Form;

use App\Entity\RestaurantSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lunchStart', null, [
                'widget' => 'single_text',
            ])
            ->add('dinnerStart', null, [
                'widget' => 'single_text',
            ])
            ->add('maxConvives')
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RestaurantSettings::class,
        ]);
    }
}
