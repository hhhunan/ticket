# Define the main service name for the application container, commonly 'app'
APP_SERVICE = app

# Default to "docker compose" for modern versions; use "docker-compose" if needed
DOCKER_COMPOSE = docker compose

.PHONY: init build up down restart composer artisan bash logs test db-refresh seed migrate
crm-build: init composer-install npm-install npm-build migrate seed
init:
	$(DOCKER_COMPOSE) up -d --build
build:
	$(DOCKER_COMPOSE) build --no-cache
up:
	$(DOCKER_COMPOSE) up -d
down:
	$(DOCKER_COMPOSE) down -v --remove-orphans
restart:
	$(DOCKER_COMPOSE) restart
ps:
	$(DOCKER_COMPOSE) ps
npm-install:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) npm install
npm-dev:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) npm run dev
npm-build:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) npm run build
composer-install:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) composer install
composer-update:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) composer update
artisan:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan $(filter-out artisan,$(MAKECMDGOALS))
migrate:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan migrate
db-refresh:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan migrate:refresh
seed:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan db:seed
bash:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) bash
logs:
	$(DOCKER_COMPOSE) logs -f $(APP_SERVICE)
test:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan test
