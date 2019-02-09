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

