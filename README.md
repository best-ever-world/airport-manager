# The best ever world Airport Manager application ツ

### by BestEverWorld ™

## Installation (lazy way)

Clone repository: `git clone git@github.com:best-ever-world/airport-manager.git`

```bash
# Run make build to build environment (see Makefile for details)
$ make build
```

## Installation (alternate way)

Clone repository: `git clone git@github.com:best-ever-world/airport-manager.git`

```bash
# already done and included into repository to simplify review processes
$ cp ./.env.dist ./.env
# already done and included into repository to simplify review processes
$ cp ./etc/.env.dist ./etc/.env
$ docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml up -d
$ docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php composer install
$ docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php bin/console doctrine:migrations:migrate
$ docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml exec php bin/console doctrine:fixtures:load --group=default
```

## How to test

- See **doc** directory for Postman Collection **airport_manager.postman_collection.json** to have request samples for local and prod environment
- Follow http://127.0.0.1:8001/api/doc link for description of API endpoints on local environment
- Follow https://airport-manager.sobol.tech/api/doc link for description of API endpoints on prod environment

## How To and Bla-Bla-Bla...
#### Enter to the Primary container

```bash
$ make enter-php
# or
$ docker compose -f ./etc/docker-compose.yml -f ./etc/docker-compose.override.yml run --rm php bash
```

#### Apply Doctrine migrations

```bash
# Enter to the Primary container and execute next command
$ ./bin/console doctrine:migrations:migrate
```

#### Load Doctrine fixtures

```bash
# Enter to the Primary container and execute next command
$ ./bin/console doctrine:fixtures:load --group=default
```

#### Dump and Debug Routes

```bash
$ ./bin/console debug:router
```

#### Tests

Running the tests is simple (*but, unfortunately no one useful unit or integration or application test was not
implemented*):

```bash
# Runs all tests
$ composer dev:test

# Runs all static analysis checks
$ composer dev:analyze

# Runs the PHPStan static analyzer
$ composer dev:analyze:phpstan

# Runs all syntax analysis checks
$ composer dev:syntax

# Runs Composer normalize to to normalize composer.json in the working directory
$ composer dev:syntax:composer-normalize

# Runs PHP Coding Standards Fixer to check issues of pre-defined coding standards and fix them if possible
$ composer dev:syntax:fix

# Runs PHP Lint to check and detect syntax errors
$ composer dev:syntax:lint

# Runs PHP Coding Standards Fixer to check and detect violations of pre-defined coding standards
$ composer dev:syntax:phpcs

# Runs Behat tests
$ composer dev:test:behavior

# Runs Behat tests, default suite
$ composer dev:test:behavior:suite-default

# Runs PHPUnit tests
$ composer dev:test:unit

# Runs PHPUnit tests and generates Html coverage report
$ composer dev:test:unit:coverage:html

# Runs PHPUnit tests and generates Text coverage reports
$ composer dev:test:unit:coverage:text

# Runs PHPUnit tests, default suite
$ composer dev:test:unit:suite-default
```

#### Stop and Drop sources to Forgot forever ( ͡° ͜ °)

```bash
$ docker compose stop
$ docker compose down
$ docker rm ...args
$ docker rmi ...args
$ rm -rf ./path/to/sources
```

## How To Improve and Common thoughts...
- Access Rules and Resources voters
- Rate limiter
- IP black\white range
- Dealing with Concurrency with Locks
- Stateless
- Caching mechanisms based on redis
- Blocking mechanisms
- Networks and Sub-Networks usage
