<?php

namespace App\Infrastructure\Food\Persistence;

use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Exception\BrandNotFoundException;
use App\Domain\Food\Repository\BrandsCatalog;
use Doctrine\ORM\EntityManagerInterface;

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
        $brand = $this->entityManager->find(Brand::class, $id);

        if (null === $brand) {
            throw new BrandNotFoundException($id);
        }

        return $brand;
    }

    public function remove(string $id): void
    {
        $brand = $this->get($id);

        $this->entityManager->remove($brand);
    }
}
