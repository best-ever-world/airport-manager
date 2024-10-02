RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

.PHONY: default
default: help

.PHONY: help
help: ## Get this help
	@echo Tasks:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY: enter-php
enter-php:
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml run --rm php bash

.PHONY: clean
clean:
	docker-compose rm -f -s -v

.PHONY: boot
boot:
	cp -n ./etc/.env.dist ./etc/.env || true
	cp -n ./etc/php/development/xdebug.ini.dist ./etc/php/development/xdebug.ini || true
	cp -n ./etc/php/development/memory_limit.ini.dist ./etc/php/development/memory_limit.ini || true
	docker network create --driver bridge  local_dev_network_best_ever_world || true

.PHONY: build
build: boot
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml up -d
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php composer install
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php bin/console doctrine:migrations:migrate --env=dev
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php bin/console doctrine:fixtures:load --env=dev --group=default

.PHONY: build-cache-ignore
build-cache-ignore: boot
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml build --no-cache
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml up -d
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php rm -rf vendor/
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php composer install
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php bin/console doctrine:migrations:migrate --env=dev
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php bin/console doctrine:fixtures:load  --env=dev --group=default

.PHONY: up
up: boot
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml up

.PHONY: upd
upd: boot
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml up -d

.PHONY: stop
stop:
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml stop
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml down

.PHONY: debug-on
debug-on:
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php cp /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini /tmp/xdebug.ini
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php sed -i 's/mode=off/mode=develop,debug/g' /tmp/xdebug.ini
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php cp -f /tmp/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml up -d --force-recreate

.PHONY: debug-off
debug-off:
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml up -d
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php cp /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini /tmp/xdebug.ini
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php sed -i 's/mode=develop,debug/mode=off/g' /tmp/xdebug.ini
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php cp -f /tmp/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml up -d --force-recreate

%:
	@:
