<?php

namespace App\Infrastructure\Race\Persistence\Repository;

use App\Domain\Race\Entity\Segment;
use App\Domain\Race\Exception\SegmentNotFoundException;
use App\Domain\Race\Repository\SegmentsCatalog;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSegmentsCatalog implements SegmentsCatalog
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function get(string $id): Segment
    {
        $segment = $this->entityManager->find(Segment::class, $id);
        if (null === $segment) {
            throw new SegmentNotFoundException($id);
        }

        return $segment;
    }

    public function add(Segment $segment): void
    {
        $this->entityManager->persist($segment);
    }

    public function getByNutritionPlanAndId(string $nutritionPlanId, string $segmentId): Segment
    {
        $segment = $this->entityManager->createQueryBuilder()
            ->select('s')
            ->from(Segment::class, 's')
            ->where('s.nutritionPlan = :nutritionPlanId')
            ->andWhere('s.id = :segmentId')
            ->setParameter('nutritionPlanId', $nutritionPlanId)
            ->setParameter('segmentId', $segmentId)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $segment) {
            throw new SegmentNotFoundException($segmentId);
        }

        return $segment;
    }
}
