<?php

namespace App\Infrastructure\User\Security;

use App\Infrastructure\User\Persistence\DoctrineUserCatalog;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<UserAdapter>
 */
final readonly class UserProvider implements UserProviderInterface
{
    public function __construct(private DoctrineUserCatalog $userRepository)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserAdapter) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return new UserAdapter($this->userRepository->getById($user->getUser()->id));
    }

    public function supportsClass(string $class): bool
    {
        return UserAdapter::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return new UserAdapter($this->userRepository->getByEmail($identifier));
    }
}
