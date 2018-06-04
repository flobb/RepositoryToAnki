DOCKER_COMPOSE=docker-compose
TOOLS = $(DOCKER_COMPOSE) run --rm tools

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
.DEFAULT_GOAL := help

##
## Docker dev environment
##---------------------------------------------------------------------------

stop: ## Kill all containers
	$(DOCKER_COMPOSE) kill
.PHONY:stop

clean: ## Remove all containers with their volumes
	$(DOCKER_COMPOSE) down -v --remove-orphans || true
.PHONY:clean

build: ## Build all docker images
	$(DOCKER_COMPOSE) pull
	$(DOCKER_COMPOSE) build
.PHONY:build

start: ## Start containers and make sure they are ready
	$(DOCKER_COMPOSE) up --no-recreate --remove-orphans -d
.PHONY:start

##
## Project setup
##---------------------------------------------------------------------------

vendors: ## Install any project dependencies
	$(TOOLS) cp ./config/parameters.yml.dist ./config/parameters.yml || true
	$(TOOLS) composer install
.PHONY:vendors

restart: clean install ## Clean containers and volumes and install the project
.PHONY:restart

install: build start vendors ## Bootstrap the whole project
.PHONY:install
