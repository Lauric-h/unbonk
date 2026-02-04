<?php

namespace App\Infrastructure\NutritionPlan\Persistence;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Exception\NutritionPlanNotFoundException;
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

    public function remove(NutritionPlan $nutritionPlan): void
    {
        $this->entityManager->remove($nutritionPlan);
    }

    public function get(string $id): NutritionPlan
    {
        $nutritionPlan = $this->entityManager->find(NutritionPlan::class, $id);

        if (null === $nutritionPlan) {
            throw new NutritionPlanNotFoundException($id);
        }

        return $nutritionPlan;
    }

    /**
     * @return NutritionPlan[]
     */
    public function findByRaceId(string $raceId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('nutritionPlan')
            ->from(NutritionPlan::class, 'nutritionPlan')
            ->where('nutritionPlan.race = :raceId')
            ->setParameter('raceId', $raceId)
            ->getQuery()
            ->getResult();
    }
}
