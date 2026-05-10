<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Security\ValueResolver;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Automatically resolves NutritionPlan entities from route parameters.
 * 
 * This allows using NutritionPlan directly as controller parameters:
 * 
 * #[Route('/nutrition-plans/{nutritionPlanId}')]
 * #[IsGranted('VIEW', subject: 'nutritionPlan')]
 * public function __invoke(NutritionPlan $nutritionPlan) { ... }
 */
final readonly class NutritionPlanValueResolver implements ValueResolverInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
    ) {
    }

    /**
     * @return iterable<NutritionPlan>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // Only resolve if the argument type is NutritionPlan
        if ($argument->getType() !== NutritionPlan::class) {
            return [];
        }

        // Try to find the nutrition plan ID from route parameters
        // Common parameter names: nutritionPlanId, id
        $nutritionPlanId = $request->attributes->get('nutritionPlanId')
            ?? $request->attributes->get('id');

        if (null === $nutritionPlanId) {
            return [];
        }

        // Load from catalog (will throw NutritionPlanNotFoundException if not found)
        $nutritionPlan = $this->nutritionPlansCatalog->get($nutritionPlanId);

        return [$nutritionPlan];
    }
}
