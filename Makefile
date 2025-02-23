PHP            ?= php

lint: stan rector cs-fix
	$(PHP) bin/console doctrine:schema:validate --skip-sync

rector:
	$(PHP) vendor/bin/rector process --config=rector.php

stan:
	$(PHP) vendor/bin/phpstan analyze -c phpstan.neon --memory-limit=-1

cs-fix:
	$(PHP) vendor/bin/php-cs-fixer fix --verbose --diff --show-progress=dots

tests:
	$(PHP) vendor/bin/phpunit tests/Unit

migrate:
	$(PHP) bin/console do:mi:mi --no-interaction --allow-no-migration