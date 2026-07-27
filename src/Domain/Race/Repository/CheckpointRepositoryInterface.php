<?php

namespace App\Domain\Race\Repository;

use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Entity\Segment;
use App\Domain\Shared\Repository\ObjectRepositoryInterface;

/**
 * @extends ObjectRepositoryInterface<Checkpoint>
 */
interface CheckpointRepositoryInterface extends ObjectRepositoryInterface
{
}