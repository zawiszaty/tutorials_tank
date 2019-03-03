.PHONY: start
start: erase up db

.PHONY: stop
stop: ## stop environment
		docker-compose -f docker-compose.dev.yml stop

.PHONY: stop-prod
stop-prod: ## stop environment
		docker-compose -f docker-compose.prod.yml stop

.PHONY: erase
erase: ## stop and delete containers, clean volumes.
		docker-compose -f docker-compose.dev.yml stop

.PHONY: build
build: ## build environment and initialize composer and project dependencies
		docker-compose -f docker-compose.dev.yml build

.PHONY: up
up: ## spin up environment
		docker-compose -f docker-compose.dev.yml up -d

.PHONY: db
db: ## recreate database
		docker-compose -f docker-compose.dev.yml exec php php bin/console d:d:c --env=dev
		docker-compose -f docker-compose.dev.yml exec php php bin/console d:s:c --env=dev
		docker-compose -f docker-compose.dev.yml exec php php bin/console d:m:m -n --env=dev

.PHONY: dev
dev: erase up composer-install db db-test

.PHONY: db-test
db-test: ## recreate database in test env
		docker-compose -f docker-compose.dev.yml exec php php bin/console d:d:c --env=test
		docker-compose -f docker-compose.dev.yml exec php php bin/console d:s:c --env=test
		docker-compose -f docker-compose.dev.yml exec php php bin/console d:m:m -n --env=test

.PHONY: test
test: phpspec behat phpunit layer style

.PHONY: behat
behat:
		docker-compose -f docker-compose.dev.yml exec php ./vendor/bin/behat

.PHONY: phpspec
phpspec:
		docker-compose -f docker-compose.dev.yml exec php ./vendor/bin/phpspec run

.PHONY: phpunit
phpunit:
		docker-compose -f docker-compose.dev.yml exec php ./vendor/bin/phpunit

.PHONY: composer-install
composer-install:
		docker-compose -f docker-compose.dev.yml exec php apt-get -y install git
		docker-compose -f docker-compose.dev.yml exec php php composer.phar install

.PHONY: composer-install-prod
composer-install-prod:
		docker-compose -f docker-compose.prod.yml exec php apt-get -y install git
		docker-compose -f docker-compose.prod.yml exec php php composer.phar install --no-dev

.PHONY: elastica
elastica:
		docker-compose -f docker-compose.dev.yml exec php php bin/console app:es

.PHONY: clear
clear:
		docker-compose -f docker-compose.dev.yml rm -v -f
		docker-compose -f docker-compose.dev.yml down

.PHONY: cs
cs: ## executes php cs fixer
		docker-compose -f docker-compose.dev.yml exec php ./vendor/bin/php-cs-fixer --no-interaction --diff -v fix

.PHONY: layer
layer: ## Check issues with layers
		docker-compose -f docker-compose.dev.yml exec php php bin/deptrac.phar analyze --formatter-graphviz=0

.PHONY: php
php: ## connect to php container
		docker-compose -f docker-compose.dev.yml exec php /bin/bash

.PHONY: php-prod
php-prod: ## connect to php container
		docker-compose -f docker-compose.prod.yml exec php /bin/bash
.PHONY: style
style: ## executes php analizers
		docker-compose -f docker-compose.dev.yml exec php ./vendor/bin/phpstan analyse -l 1 -c phpstan.neon src

.PHONY: commit
commit: cs
		- git add .
		- git commit -m "$(m)"

.PHONY: front-prod
front-prod:
		- docker-compose -f docker-compose.prod.yml exec frontend /bin/bash

.PHONY: prod-up
prod-up: ## make porduction
		- docker-compose -f docker-compose.prod.yml up -d

.PHONY: prod-db
prod-db: ## make porduction db
		- docker-compose -f docker-compose.prod.yml exec php php bin/console d:d:c --env=prod
		- docker-compose -f docker-compose.prod.yml exec php php bin/console d:s:c --env=prod
		- docker-compose -f docker-compose.prod.yml exec php php bin/console d:m:m -n --env=prod

.PHONY: prod
prod: prod-up composer-install prod-db