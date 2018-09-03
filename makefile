.PHONY: start
start:
		  - docker-compose up -d
          - docker-compose exec php ./vendor/bin/phpspec run