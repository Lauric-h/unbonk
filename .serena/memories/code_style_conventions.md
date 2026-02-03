# Code Style & Conventions - Unbonk Project

## PHP Standards
- **PHP Version**: 8.2+
- **PSR-4 Autoloading**: `App\` namespace maps to `src/`
- **Strict Types**: Use `declare(strict_types=1);` in all PHP files

## Architecture Patterns

### Hexagonal Architecture (Ports & Adapters)
```
src/
├── Domain/          # Core business logic (entities, value objects, domain events)
├── Application/     # Use cases (Commands, Queries, Handlers, ReadModels)
├── Infrastructure/  # Technical implementations (Doctrine, adapters, event subscribers)
├── UI/              # Controllers (REST API and Web)
└── SharedKernel/    # Cross-cutting concerns (IdGenerator)
```

### DDD Conventions
- **Entities**: Rich domain models with behavior
- **Value Objects**: Immutable objects (Distance, Ascent, Descent, Calories, Carbs, Duration)
- **Aggregates**: Race is an aggregate root containing Checkpoints and Segments
- **Domain Events**: `RaceCreated`, `RaceDeleted`, `RaceCheckpointsChanged`
- **Repositories (Catalogs)**: Named as `*Catalog` (e.g., `RacesCatalog`, `FoodsCatalog`)

### CQRS Pattern
- **Commands**: `src/Application/{Context}/UseCase/{Action}/{Action}Command.php`
- **CommandHandlers**: `src/Application/{Context}/UseCase/{Action}/{Action}CommandHandler.php`
- **Queries**: Similar structure with `Query` suffix
- **ReadModels**: DTOs for query results in `src/Application/{Context}/ReadModel/`

## Naming Conventions
- **Classes**: PascalCase
- **Methods/Properties**: camelCase
- **Constants**: SCREAMING_SNAKE_CASE
- **Files**: Match class name exactly

### Controller Naming
- One action per controller (REST)
- Named by action: `CreateRaceController`, `GetRaceController`, `ListRaceController`

### Entity Patterns
- Use static factory methods: `Entity::create(...)`
- Private constructors when using factory methods
- Domain validation in entity methods

## Doctrine
- XML mapping files: `src/Infrastructure/{Context}/Persistence/*.orm.xml`
- Repository classes: `Doctrine*Catalog`

## Type Hints
- Always use return types
- Use nullable types with `?` prefix
- Use union types sparingly
- Collection types: `Collection<int, EntityType>`
