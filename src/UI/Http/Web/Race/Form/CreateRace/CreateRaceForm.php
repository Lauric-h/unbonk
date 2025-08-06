<?php

namespace App\UI\Http\Web\Race\Form\CreateRace;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CreateRaceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('date', DateType::class, [
                'required' => true,
            ])
            ->add('distance', IntegerType::class, [
                'required' => true,
            ])
            ->add('ascent', IntegerType::class, [
                'required' => true,
            ])
            ->add('descent', IntegerType::class, [
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'required' => true,
            ])
            ->add('postalCode', TextType::class, [
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateRaceModel::class,
        ]);
    }
}
