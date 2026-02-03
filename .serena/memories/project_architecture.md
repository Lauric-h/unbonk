# Unbonk Project Architecture

## Purpose
Trail running nutrition planner - helps endurance athletes plan their nutrition strategy for races.

## Tech Stack
- **Framework**: Symfony 7.2
- **PHP**: 8.2+
- **ORM**: Doctrine 3
- **Database**: PostgreSQL (assumed from Doctrine DBAL)

## Bounded Contexts

### 1. User Context (`src/Domain/User/`)
- User registration and authentication
- Entities: `User`

### 2. Race Context (`src/Domain/Race/`)
- Race management with checkpoints and segments
- **Aggregate Root**: `Race`
- **Entities**: 
  - `Checkpoint` (abstract): `StartCheckpoint`, `FinishCheckpoint`, `AidStationCheckpoint`, `IntermediateCheckpoint`
  - `Segment`: computed from checkpoints
  - `NutritionPlan`, `NutritionSegment`, `NutritionItem` (legacy - being refactored)
- **Value Objects**: `Address`, `Profile`, `MetricsFromStart`, `Quantity`
- **Domain Events**: `RaceCreated`, `RaceDeleted`, `RaceCheckpointsChanged`

### 3. NutritionPlan Context (`src/Domain/NutritionPlan/`)
- Nutrition planning per race segments
- **Aggregate Root**: `NutritionPlan`
- **Entities**: `Segment`, `NutritionItem`
- **Value Objects**: `SegmentPoint`, `Quantity`
- **Ports**: `RaceOwnershipPort`, `ExternalFoodPort`

### 4. Food Context (`src/Domain/Food/`)
- Food and brand catalog management
- **Aggregate Root**: `Brand`
- **Entities**: `Food`
- **Value Objects**: `IngestionType`

## Integration Events
Events that cross bounded context boundaries:
- `RaceCreatedIntegrationEvent` → triggers NutritionPlan creation
- `RaceDeletedIntegrationEvent` → triggers NutritionPlan deletion
- `RaceCheckpointsChangedIntegrationEvent` → triggers segment rebuild

## Folder Structure
```
src/
├── Domain/{Context}/
│   ├── Entity/
│   ├── Repository/       # Interfaces (Catalogs)
│   ├── Port/             # Secondary ports
│   ├── Event/            # Domain events
│   ├── Exception/
│   └── DTO/
├── Application/{Context}/
│   ├── UseCase/{Action}/ # Command/Query + Handler
│   ├── ReadModel/        # Query DTOs
│   ├── Service/          # Application ports
│   └── Factory/
├── Infrastructure/{Context}/
│   ├── Persistence/      # Doctrine implementations + ORM mappings
│   ├── Adapter/          # Port implementations
│   ├── Service/
│   └── EventSubscriber/  # Integration event handlers
└── UI/Http/
    ├── Rest/{Context}/   # REST API controllers
    └── Web/{Context}/    # Web form controllers
```

## Current State Notes
- **Duplication Issue**: `Segment` exists in both Race and NutritionPlan contexts
- Races are currently created manually (no external API integration)
- NutritionPlan is linked to Race via `raceId` and integration events
