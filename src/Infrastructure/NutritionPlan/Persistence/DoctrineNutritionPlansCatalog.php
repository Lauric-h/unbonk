<?php

namespace App\Infrastructure\NutritionPlan\Persistence;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Exception\NutritionPlanNotFoundException;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NutritionPlan>
 */
class DoctrineNutritionPlansCatalog extends ServiceEntityRepository implements NutritionPlansCatalog
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NutritionPlan::class);
    }

    public function add(NutritionPlan $nutritionPlan): void
    {
        $this->getEntityManager()->persist($nutritionPlan);
    }

    public function remove(NutritionPlan $nutritionPlan): void
    {
        $this->getEntityManager()->remove($nutritionPlan);
    }

    public function get(string $id): NutritionPlan
    {
        $nutritionPlan = $this->find($id);

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
        return $this->createQueryBuilder('nutritionPlan')
            ->where('nutritionPlan.runnerRace = :raceId')
            ->setParameter('raceId', $raceId)
            ->getQuery()
            ->getResult();
    }

    public function getByRunner(string $runnerId): array
    {
        return $this->createQueryBuilder('nutritionPlan')
            ->innerJoin('nutritionPlan.race', 'r')
            ->where('r.runnerId = :runnerId')
            ->setParameter('runnerId', $runnerId)
            ->orderBy('r.startDateTime', 'DESC')
            ->orderBy('nutritionPlan.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
