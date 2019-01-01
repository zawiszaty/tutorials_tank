.PHONY: start
start:
	nie działa xD
  - docker-compose up -d
  - docker-compose exec php ./vendor/bin/phpspec run
