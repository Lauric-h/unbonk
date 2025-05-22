<?php

namespace App\Infrastructure\Food\Persistence;

use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Exception\BrandNotFoundException;
use App\Domain\Food\Repository\BrandsCatalog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Brand>
 */
class DoctrineBrandsCatalog extends ServiceEntityRepository implements BrandsCatalog
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    public function add(Brand $brand): void
    {
        $this->getEntityManager()->persist($brand);
    }

    public function get(string $id): Brand
    {
        try {
            $brand = $this->getEntityManager()->find(Brand::class, $id);
            if (null === $brand) {
                throw new BrandNotFoundException($id);
            }
        } catch (ORMInvalidArgumentException|ORMException $exception) {
            throw new \LogicException(sprintf('Impossible to retrieve brand with id %s', $id));
        }

        return $brand;
    }

    public function remove(Brand $brand): void
    {
        $this->getEntityManager()->remove($brand);
    }

    public function getAll(): array
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b FROM App\Domain\Food\Entity\Brand b')
            ->getResult();
    }

    public function exists(string $name): bool
    {
        $count = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(brand.id)')
            ->from(Brand::class, 'brand')
            ->where('brand.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }
}
