<?php

namespace App\Infrastructure\Race\Persistence\Repository;

use App\Domain\NutritionPlan\Exception\NutritionPlanNotFoundException;
use App\Domain\Race\Entity\NutritionPlan;
use App\Domain\Race\Repository\NutritionPlansCatalog;
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

    public function getByRaceId(string $raceId): NutritionPlan
    {
        $nutritionPlan = $this->entityManager->createQueryBuilder()
            ->select('nutritionPlan')
            ->from(NutritionPlan::class, 'nutritionPlan')
            ->where('nutritionPlan.raceId = :raceId')
            ->setParameter('raceId', $raceId)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $nutritionPlan) {
            throw new NutritionPlanNotFoundException($raceId);
        }

        return $nutritionPlan;
    }
}
