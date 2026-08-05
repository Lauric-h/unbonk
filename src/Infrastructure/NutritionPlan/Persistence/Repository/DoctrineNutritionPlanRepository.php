<?php

namespace App\Infrastructure\NutritionPlan\Persistence\Repository;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlanRepositoryInterface;
use App\Infrastructure\Shared\Persistence\Repository\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NutritionPlan>
 */
class DoctrineNutritionPlanRepository extends ServiceEntityRepository implements NutritionPlanRepositoryInterface
{
    use DoctrineRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NutritionPlan::class);
    }

    public function existsForRunnerRace(string $runnerRaceId): bool
    {
        $result = $this->createQueryBuilder('qb')
            ->select('1')
            ->from(NutritionPlan::class, 'np')
            ->where('np.runnerRaceId = :runnerRaceId')
            ->setParameter('runnerRaceId', $runnerRaceId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return null !== $result;
    }

    public function findByRunnerRaceId(string $runnerRaceId): ?NutritionPlan
    {
        return $this->findOneBy(['runnerRaceId' => $runnerRaceId]);
    }

    public function deleteByRunnerRaceId(string $runnerRaceId): void
    {
        $this->createQueryBuilder('n')
            ->delete(NutritionPlan::class, 'np')
            ->where('np.runnerRaceId = :runnerRaceId')
            ->setParameter('runnerRaceId', $runnerRaceId)
            ->getQuery()
            ->execute();
    }
}