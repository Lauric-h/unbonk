<?php

namespace App\Infrastructure\Food\Persistence;

use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Exception\BrandNotFoundException;
use App\Domain\Food\Repository\BrandsCatalog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;

class DoctrineBrandsCatalog implements BrandsCatalog
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function add(Brand $brand): void
    {
        $this->entityManager->persist($brand);
    }

    public function get(string $id): Brand
    {
        try {
            $brand = $this->entityManager->find(Brand::class, $id);
            if (null === $brand) {
                throw new BrandNotFoundException($id);
            }
        } catch (ORMInvalidArgumentException|ORMException $exception) {
            throw new \LogicException(sprintf('Impossible to retrieve brand with id %s', $id));
        }

        return $brand;
    }

    public function remove(string $id): void
    {
        try {
            $brand = $this->entityManager->getReference(
                Brand::class,
                $id,
            );
            if (null === $brand) {
                throw new BrandNotFoundException($id);
            }

            $this->entityManager->remove($brand);
        } catch (ORMInvalidArgumentException|ORMException $exception) {
            throw new \LogicException(sprintf('Impossible to remove brand with id %s', $id));
        }
    }
}
