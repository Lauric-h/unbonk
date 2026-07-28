<?php

namespace App\Infrastructure\NutritionPlan\Persistence\Repository;

use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;
use App\Domain\NutritionPlan\Repository\SegmentNutritionPlanRepositoryInterface;
use App\Infrastructure\Shared\Persistence\Repository\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SegmentNutritionPlan>
 */
class DoctrineSegmentNutritionPlanRepository extends ServiceEntityRepository implements SegmentNutritionPlanRepositoryInterface
{
    use DoctrineRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SegmentNutritionPlan::class);
    }
}