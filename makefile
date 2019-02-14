.PHONY: start
start: erase up db

.PHONY: stop
stop: ## stop environment
		docker-compose stop

.PHONY: erase
erase: ## stop and delete containers, clean volumes.
		docker-compose stop

.PHONY: build
build: ## build environment and initialize composer and project dependencies
		docker-compose build

.PHONY: up
up: ## spin up environment
		docker-compose up -d

.PHONY: db
db: ## recreate database
		docker-compose exec php php bin/console d:d:c --env=dev
		docker-compose exec php php bin/console d:s:c --env=dev
		docker-compose exec php php bin/console d:m:m -n --env=dev

.PHONY: dev
dev: erase up composer-install db db-test

.PHONY: db-test
db-test: ## recreate database in test env
		docker-compose exec php php bin/console d:d:c --env=test
		docker-compose exec php php bin/console d:s:c --env=test
		docker-compose exec php php bin/console d:m:m -n --env=test

.PHONY: test
test: phpspec behat phpunit layer style

.PHONY: behat
behat:
		docker-compose exec php ./vendor/bin/behat

.PHONY: phpspec
phpspec:
		docker-compose exec php ./vendor/bin/phpspec run

.PHONY: phpunit
phpunit:
		docker-compose exec php ./vendor/bin/phpunit

.PHONY: composer-install
composer-install:
		docker-compose exec php apt-get -y install git
		docker-compose exec php php composer.phar install

.PHONY: elastica
elastica:
		docker-compose exec php php bin/console f:e:p

.PHONY: clear
clear:
		docker-compose rm -v -f
		docker-compose down

.PHONY: cs
cs: ## executes php cs fixer
		docker-compose exec php ./vendor/bin/php-cs-fixer --no-interaction --diff -v fix

.PHONY: layer
layer: ## Check issues with layers
		docker-compose exec php php bin/deptrac.phar analyze --formatter-graphviz=0

.PHONY: php
php: ## connect to php container
		docker-compose exec php /bin/bash

.PHONY: style
style: ## executes php analizers
		docker-compose exec php ./vendor/bin/phpstan analyse -l 1 -c phpstan.neon src

.PHONY: commit
commit: cs
		- git add .
		- git commit -m "$(m)"