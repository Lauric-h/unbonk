<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Form\NutritionPlan;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateNutritionPlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du plan',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Mon plan UTMB 2024',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UpdateNutritionPlanModel::class,
        ]);
    }
}
