<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\User\Security\Voter;

use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\Security\UserAdapter;
use App\Infrastructure\User\Security\Voter\ImportedRaceVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class ImportedRaceVoterTest extends TestCase
{
    private ImportedRaceVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new ImportedRaceVoter();
    }

    public function testOwnerCanView(): void
    {
        // Given
        $user = new User('user-123', 'owner@example.com', 'password');
        $userAdapter = new UserAdapter($user);
        $race = new ImportedRace(
            id: 'race-id',
            runnerId: 'user-123',  // Same user
            name: 'UTMB',
            distance: 170000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-08-30 18:00:00'),
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            externalRaceId: 'external-race-123',
            externalEventId: 'external-event-456',
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($userAdapter);

        // When
        $result = $this->voter->vote($token, $race, ['VIEW']);

        // Then
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testOwnerCanCreatePlan(): void
    {
        // Given
        $user = new User('user-123', 'owner@example.com', 'password');
        $userAdapter = new UserAdapter($user);
        $race = new ImportedRace(
            id: 'race-id',
            runnerId: 'user-123',  // Same user
            name: 'UTMB',
            distance: 170000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-08-30 18:00:00'),
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            externalRaceId: 'external-race-123',
            externalEventId: 'external-event-456',
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($userAdapter);

        // When
        $result = $this->voter->vote($token, $race, ['CREATE_PLAN']);

        // Then
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testOwnerCanDelete(): void
    {
        // Given
        $user = new User('user-123', 'owner@example.com', 'password');
        $userAdapter = new UserAdapter($user);
        $race = new ImportedRace(
            id: 'race-id',
            runnerId: 'user-123',  // Same user
            name: 'UTMB',
            distance: 170000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-08-30 18:00:00'),
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            externalRaceId: 'external-race-123',
            externalEventId: 'external-event-456',
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($userAdapter);

        // When
        $result = $this->voter->vote($token, $race, ['DELETE']);

        // Then
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testNonOwnerCannotView(): void
    {
        // Given
        $user = new User('user-456', 'other@example.com', 'password');
        $userAdapter = new UserAdapter($user);
        $race = new ImportedRace(
            id: 'race-id',
            runnerId: 'user-123',  // Different user
            name: 'UTMB',
            distance: 170000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-08-30 18:00:00'),
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            externalRaceId: 'external-race-123',
            externalEventId: 'external-event-456',
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($userAdapter);

        // When
        $result = $this->voter->vote($token, $race, ['VIEW']);

        // Then
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testNonOwnerCannotCreatePlan(): void
    {
        // Given
        $user = new User('user-456', 'other@example.com', 'password');
        $userAdapter = new UserAdapter($user);
        $race = new ImportedRace(
            id: 'race-id',
            runnerId: 'user-123',  // Different user
            name: 'UTMB',
            distance: 170000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-08-30 18:00:00'),
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            externalRaceId: 'external-race-123',
            externalEventId: 'external-event-456',
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($userAdapter);

        // When
        $result = $this->voter->vote($token, $race, ['CREATE_PLAN']);

        // Then
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testUnauthenticatedUserCannotAccess(): void
    {
        // Given
        $race = new ImportedRace(
            id: 'race-id',
            runnerId: 'user-123',
            name: 'UTMB',
            distance: 170000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-08-30 18:00:00'),
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            externalRaceId: 'external-race-123',
            externalEventId: 'external-event-456',
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(null);  // Not authenticated

        // When
        $result = $this->voter->vote($token, $race, ['VIEW']);

        // Then
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testDoesNotSupportOtherSubjects(): void
    {
        // Given
        $user = new User('user-123', 'owner@example.com', 'password');
        $userAdapter = new UserAdapter($user);
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($userAdapter);

        // When
        $result = $this->voter->vote($token, new \stdClass(), ['VIEW']);

        // Then
        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    public function testDoesNotSupportOtherAttributes(): void
    {
        // Given
        $user = new User('user-123', 'owner@example.com', 'password');
        $userAdapter = new UserAdapter($user);
        $race = new ImportedRace(
            id: 'race-id',
            runnerId: 'user-123',
            name: 'UTMB',
            distance: 170000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-08-30 18:00:00'),
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            externalRaceId: 'external-race-123',
            externalEventId: 'external-event-456',
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($userAdapter);

        // When
        $result = $this->voter->vote($token, $race, ['UNKNOWN_PERMISSION']);

        // Then
        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }
}
