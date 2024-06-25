#!make
include .env
export

DOCKER_COMPOSE=docker-compose -f ./docker/docker-compose.yml --project-directory=./docker
DOCKER_EXEC=docker exec ${PROJECT_NAME}.php-fpm

CURRENT_UID := $(shell id -u)
CURRENT_USER := $(shell whoami)

export CURRENT_UID
export CURRENT_USER

.PHONY: init
init: build up composer

.PHONY: up
up:
	$(DOCKER_COMPOSE) up -d

.PHONY: down
down:
	$(DOCKER_COMPOSE) down

.PHONY: restart
restart: $(DOCKER_COMPOSE) down up

.PHONY: ps
ps:
	$(DOCKER_COMPOSE) ps

.PHONY: build
build:
	$(DOCKER_COMPOSE) build --force-rm

.PHONY: composer
composer:
	$(DOCKER_EXEC) composer install -n

.PHONY: exec
exec:
	docker exec -it ${PROJECT_NAME}.php-fpm bash

.PHONY: logs
logs:
	$(DOCKER_COMPOSE) logs

.PHONY: db
db:
	docker exec -it ${PROJECT_NAME}.db bash

.PHONY: fixer
fixer:
	docker exec -it ${PROJECT_NAME}.php-fpm ./vendor/bin/php-cs-fixer --config=./.php-cs-fixer.dist.php fix --diff -v ./

.PHONY: fixer-diff
fixer-diff:
	docker exec -it ${PROJECT_NAME}.php-fpm ./vendor/bin/php-cs-fixer --config=./.php-cs-fixer.dist.php fix --diff --dry-run -v ./
