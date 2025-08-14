<?php

namespace App\UI\Http\Web\Race\Form\AddCheckpoint;

use App\Domain\Race\Entity\CheckpointType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AddCheckpointForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('location', TextType::class, [
                'required' => true,
            ])
            ->add('checkpointType', EnumType::class, [
                'required' => true,
                'class' => CheckpointType::class,
            ])
            ->add('estimatedTimeInMinutes', IntegerType::class, [
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddCheckpointModel::class,
        ]);
    }
}
