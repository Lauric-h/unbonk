<?php

declare(strict_types=1);

namespace App\Application\Shared\Security;

/**
 * Provides the ID of the currently authenticated user.
 *
 * This interface allows bounded contexts to access the current user's ID
 * without depending on the User context or authentication infrastructure.
 */
interface CurrentUserIdProvider
{
    /**
     * Get the ID of the currently authenticated user.
     *
     * @throws \RuntimeException if no user is authenticated
     */
    public function getCurrentUserId(): string;
}
