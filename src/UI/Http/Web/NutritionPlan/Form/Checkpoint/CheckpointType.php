<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Form\Checkpoint;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class CheckpointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du checkpoint',
                'required' => true,
            ])
            ->add('location', TextType::class, [
                'label' => 'Lieu',
                'required' => true,
            ])
            ->add('distanceFromStart', IntegerType::class, [
                'label' => 'Distance depuis le départ (mètres)',
                'required' => true,
            ])
            ->add('ascentFromStart', IntegerType::class, [
                'label' => 'D+ cumulé (mètres)',
                'required' => true,
            ])
            ->add('descentFromStart', IntegerType::class, [
                'label' => 'D- cumulé (mètres)',
                'required' => true,
            ])
            ->add('cutoffTime', DateTimeType::class, [
                'label' => 'Heure limite (cutoff)',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('assistanceAllowed', CheckboxType::class, [
                'label' => 'Assistance autorisée',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CheckpointModel::class,
        ]);
    }
}
