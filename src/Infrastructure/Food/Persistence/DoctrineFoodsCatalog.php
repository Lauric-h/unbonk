<?php

namespace App\Infrastructure\Food\Persistence;

use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\Domain\Food\Exception\FoodNotFoundException;
use App\Domain\Food\Repository\FoodsCatalog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;

class DoctrineFoodsCatalog implements FoodsCatalog
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function add(Food $food): void
    {
        try {
            $this->entityManager->persist($food);
        } catch (ORMInvalidArgumentException|ORMException $exception) {
            throw new \LogicException(sprintf('Impossible to add food with id %s', $food->id));
        }
    }

    public function get(string $id): Food
    {
        try {
            $food = $this->entityManager->find(Food::class, $id);
            if (null === $food) {
                throw new FoodNotFoundException($id);
            }
        } catch (ORMInvalidArgumentException|ORMException $exception) {
            throw new \LogicException(sprintf('Impossible to retrieve food with id %s', $id));
        }

        return $food;
    }

    public function remove(string $id): void
    {
        try {
            $food = $this->entityManager->getReference(
                Food::class,
                $id,
            );
            if (null === $food) {
                throw new FoodNotFoundException($id);
            }

            $this->entityManager->remove($food);
        } catch (ORMInvalidArgumentException|ORMException $exception) {
            throw new \LogicException(sprintf('Impossible to remove food with id %s', $id));
        }
    }

    /**
     * @return Food[]
     */
    public function getAll(?string $brandId, ?string $name, ?IngestionType $ingestionType): array
    {
        $qb = $this->entityManager
            ->createQueryBuilder()
            ->select('f')
            ->from(Food::class, 'f');

        if (null !== $brandId) {
            $qb->join('f.brand', 'b')
                ->andWhere('b.id = :brandId')
                ->setParameter('brandId', $brandId);
        }

        if (null !== $name) {
            $qb->andWhere('f.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if (null !== $ingestionType) {
            $qb->andWhere('f.ingestionType = :ingestionType')
            ->setParameter('ingestionType', $ingestionType);
        }

        return $qb->getQuery()->getResult();
    }
}
