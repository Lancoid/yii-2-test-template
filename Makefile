SHELL=/bin/bash
DCC=docker compose

.DEFAULT_GOAL := help
.PHONY: start up up-force stop down build composer-install schema-update bash pre-commit asset help logs

up: ## Start containers in detached mode and remove orphans
	$(DCC) up -d --remove-orphans

up-force: ## Start containers with force recreate and remove orphans
	$(DCC) up -d --force-recreate --remove-orphans --timeout 5

stop: ## Stop running containers
	$(DCC) stop --timeout 5

down: stop ## Stop and remove containers, networks, volumes, and images
	$(DCC) down

build: ## Build containers and pull latest images
	$(DCC) build --pull

composer-install: ## Install PHP dependencies using Composer
	$(DCC) exec php composer i

schema-update: ## Run database migrations (non-interactive)
	$(DCC) exec php ./yii migrate/up --interactive=0

bash: ## Open bash shell in PHP container
	$(DCC) exec php bash

pre-commit: ## Run Composer pre-commit hooks
	$(DCC) exec php composer pre-commit

asset: ## Publish frontend assets
	$(DCC) exec php ./assets-publish.sh

logs: ## Show container logs (follow mode)
	$(DCC) logs -f

help: ## Show available Makefile commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'