<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Security\Voter;

use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Voter for ImportedRace authorization.
 * Checks if the current user owns the ImportedRace.
 *
 * @extends Voter<string, ImportedRace>
 */
class ImportedRaceVoter extends Voter
{
    public const string EDIT = 'EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof ImportedRace) {
            return false;
        }

        return self::EDIT === $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $userInterface = $token->getUser();

        // User must be authenticated
        if (!$userInterface instanceof UserAdapter) {
            return false;
        }

        $user = $userInterface->getUser();

        /** @var ImportedRace $race */
        $race = $subject;

        return $this->canAccess($race, $user);
    }

    private function canAccess(ImportedRace $race, User $user): bool
    {
        // User can access the race if they are the runner
        return $race->runnerId === $user->id;
    }
}
