<?php

namespace App\UI\Http\Rest\NutritionPlan\Request;

final class UpdateNutritionItemRequest
{
    public function __construct(public int $quantity)
    {
    }
}
