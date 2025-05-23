<?php

namespace App\UI\Http\Web\Food\Form\UpdateFood;

use App\Domain\Food\Entity\IngestionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UpdateFoodForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('carbs', IntegerType::class)
            ->add('ingestionType', EnumType::class, [
                'class' => IngestionType::class,
            ])
            ->add('calories', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UpdateFoodModel::class,
        ]);
    }
}
