<?php

namespace App\Infrastructure\Shared\Persistence\Repository;
use App\Domain\Shared\Exception\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method EntityManagerInterface getEntityManager()
 * @method string getClassName()
 */
trait DoctrineRepositoryTrait
{
    public function get(mixed $id): object
    {
        $object = $this->find($id);

        if ($object === null) {
            throw new NotFoundException(\sprintf('Entity %s with %s not found', $this->getClassName(), $id));
        }

        return $object;
    }

    public function add(object $object): void
    {
        $this->getEntityManager()->persist($object);
    }

    public function update(object $object): void
    {
        $this->getEntityManager()->persist($object);
    }

    public function remove(object $object): void
    {
        $this->getEntityManager()->remove($object);
    }

    public function write(): void
    {
        $this->getEntityManager()->flush();
    }

    public function clear(): void
    {
        $this->getEntityManager()->clear();
    }

    public function detach(object $object): void
    {
        $this->getEntityManager()->detach($object);
    }
}
