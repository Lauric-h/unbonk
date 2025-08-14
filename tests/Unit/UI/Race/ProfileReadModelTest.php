<?php

namespace App\Tests\Unit\UI\Race;

use App\Application\Race\ReadModel\ProfileReadModel;
use App\Domain\Race\Entity\Profile;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use PHPUnit\Framework\TestCase;

final class ProfileReadModelTest extends TestCase
{
    public function testFromDomain(): void
    {
        $domain = Profile::create(new Distance(42), new Ascent(2000), new Descent(2500));

        $expected = new ProfileReadModel(42, 2000, 2500);

        $actual = ProfileReadModel::fromDomain($domain);

        $this->assertEquals($expected, $actual);
    }
}
