<?php

namespace App\Infrastructure\NutritionPlan\Persistence;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineNutritionPlansCatalog implements NutritionPlansCatalog
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function add(NutritionPlan $nutritionPlan): void
    {
        $this->entityManager->persist($nutritionPlan);
    }
}
