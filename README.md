# Tutorials Tank
[![Build Status](https://travis-ci.com/zawiszaty/tutorials_tank.svg?branch=master)](https://travis-ci.com/zawiszaty/tutorials_tank)
[![StyleCI](https://github.styleci.io/repos/141673154/shield?branch=master)](https://github.styleci.io/repos/141673154)

### How to run Linux or Mac:
* You must have install docker, docker-compose locally 
* Copy .env.dist to .env file 
```bash
$ sysctl -w vm.max_map_count=262144 
$ chmod 777 symfony/public/thumbnails
$ chmod 777 symfony/public/avatars
$ make dev
$ php bin/console fos:oauth-server:create-client --redirect-uri="" --grant-type="password"
$ copy client_secret and client_id to web-app/src/axios/env.js example is in web-app/src/axios/env.js.dist 
```
* Go to http://localhost in your web browser and start hacking
* Go to http://localhost/api/doc in your web browser to view a api docs

### How to run Windows:
* You must have install docker, docker-compose and composer locally if you are not a linux or mac user
* Copy .env.dist to .env file 
```bash
$ composer install
$ docker-compose run -d 
$ docker-compose exec php php bin/console d:d:c
$ docker-compose exec php php bin/console d:s:c
$ docker-compose exec php php bin/console d:m:l -n
```
* Go to http://localhost in your web browser and start hacking
* Go to http://localhost/api/doc in your web browser to view a api docs

### How to run test:
```bash
$ make test
```

## Implementations

- [x] Environment in Docker
- [x] Command Bus, Query Bus, Event Bus
- [x] Event Store
- [x] Elasticsearch Read Model
- [x] Rest API
- [x] Continuous integration with Travis.Ci and Style.Ci
- [x] RabbitMq Async Event Subscribers
- [x] RabbitMq Async Email Sender
- [ ] React Web UI
- [x] Nelmio API Doc
- [x] Makefile Runner
