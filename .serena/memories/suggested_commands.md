# Suggested Commands for Unbonk Project

## Development Commands (via Makefile)

### Linting & Code Quality
```bash
make lint          # Run all quality checks (phpstan, rector, cs-fix, schema validate)
make stan          # Static analysis with PHPStan
make rector        # Rector automated refactoring
make cs-fix        # PHP-CS-Fixer code style
```

### Testing
```bash
make tests         # Run PHPUnit tests (Unit tests only)
```

### Database
```bash
make migrate       # Run Doctrine migrations
php bin/console do:mi:mi --no-interaction --allow-no-migration  # Full migration command
```

## Symfony Console Commands
```bash
php bin/console cache:clear          # Clear cache
php bin/console doctrine:schema:validate  # Validate DB schema
php bin/console make:entity          # Generate entity
php bin/console make:migration       # Generate migration
```

## Composer
```bash
composer install     # Install dependencies
composer update      # Update dependencies
composer dump-autoload  # Regenerate autoloader
```

## Post-Task Checklist
After completing any code changes, run:
```bash
make lint
make tests
```
