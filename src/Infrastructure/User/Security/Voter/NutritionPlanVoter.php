<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Security\Voter;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Voter for NutritionPlan authorization.
 * Checks if the current user owns the NutritionPlan.
 *
 * @extends Voter<string, NutritionPlan>
 */
class NutritionPlanVoter extends Voter
{
    // Only one role for now
    public const string EDIT = 'EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof NutritionPlan) {
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

        /** @var NutritionPlan $nutritionPlan */
        $nutritionPlan = $subject;

        return $this->canAccess($nutritionPlan, $user);
    }

    private function canAccess(NutritionPlan $nutritionPlan, User $user): bool
    {
        return $nutritionPlan->runnerRace->runnerId === $user->id;
    }
}
