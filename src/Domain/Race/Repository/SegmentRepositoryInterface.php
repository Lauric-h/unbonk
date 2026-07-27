<?php

namespace App\Domain\Race\Repository;

use App\Domain\Race\Entity\RunnerRace;
use App\Domain\Race\Entity\Segment;
use App\Domain\Shared\Repository\ObjectRepositoryInterface;

/**
 * @extends ObjectRepositoryInterface<Segment>
 */
interface SegmentRepositoryInterface extends ObjectRepositoryInterface
{
}