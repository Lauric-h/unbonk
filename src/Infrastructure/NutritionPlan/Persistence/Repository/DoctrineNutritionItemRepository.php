<?php

namespace App\Infrastructure\NutritionPlan\Persistence\Repository;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlanRepositoryInterface;
use App\Infrastructure\Shared\Persistence\Repository\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NutritionItem>
 */
class DoctrineNutritionItemRepository extends ServiceEntityRepository implements NutritionPlanRepositoryInterface
{
    use DoctrineRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NutritionPlanRepositoryInterface::class);
    }
}