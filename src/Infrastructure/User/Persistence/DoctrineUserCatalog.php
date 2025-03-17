<?php

namespace App\Infrastructure\User\Persistence;

use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserCatalog;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserCatalog implements UserCatalog
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getByEmail(string $email): User
    {
        $user = $this->entityManager->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw new UserNotFoundException($email);
        }

        return $user;
    }

    public function getById(string $id): User
    {
        $user = $this->entityManager->find(User::class, $id);

        if (null === $user) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }
}
