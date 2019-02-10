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
		docker-compose exec php php bin/console d:d:c
		docker-compose exec php php bin/console d:s:c
		docker-compose exec php php bin/console d:m:m -n

.PHONY: dev
dev: erase up composer-install db db-test

.PHONY: db-test
db-test: ## recreate database in test env
		docker-compose exec php php bin/console d:d:c --env=test
		docker-compose exec php php bin/console d:s:c --env=test
		docker-compose exec php php bin/console d:m:m -n --env=test


.PHONY: test
test: phpspec behat elastica phpunit

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