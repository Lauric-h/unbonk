<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Security\Voter;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Voter for NutritionPlan authorization.
 * Checks if the current user owns the NutritionPlan.
 */
class NutritionPlanVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // Only vote on NutritionPlan objects
        if (!$subject instanceof NutritionPlan) {
            return false;
        }

        // Only vote if attribute is one we care about
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // User must be logged in
        if (!$user instanceof User) {
            return false;
        }

        /** @var NutritionPlan $nutritionPlan */
        $nutritionPlan = $subject;

        // Check ownership
        return $this->canAccess($nutritionPlan, $user);
    }

    private function canAccess(NutritionPlan $nutritionPlan, User $user): bool
    {
        // A user can access a nutrition plan if they own the associated race
        return $nutritionPlan->race->runnerId === $user->id;
    }
}
