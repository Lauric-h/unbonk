<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Security;

use App\Application\Shared\Security\CurrentUserIdProvider;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class SymfonyCurrentUserIdProvider implements CurrentUserIdProvider
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function getCurrentUserId(): string
    {
        $user = $this->security->getUser();

        if (!$user instanceof UserAdapter) {
            throw new \RuntimeException('No authenticated user found');
        }

        return $user->getUser()->id;
    }
}
