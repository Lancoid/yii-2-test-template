# Yii Test App

A modular Yii 2 application template with Core, Site, and User modules. It uses MySQL, Redis cache, optional Sentry error reporting, and follows modern PHP 8.4 practices.  
Development and tooling are Docker-first, with Composer for dependencies and Codeception for testing.

## Overview
- Framework: Yii 2 (~2.0.45) with Bootstrap 5 UI helpers
- Language: PHP >= 8.4
- Package manager: Composer 2
- Caching: Redis (yii2-redis)
- Database: MySQL (via PDO)
- Error tracking: Sentry (optional)
- Testing: Codeception (unit/functional), Yii2 module
- Static analysis/formatting: PHPStan, Rector, PHP-CS-Fixer
- Orchestration: Docker Compose (optional, recommended for local dev)

## Requirements
- Linux/macOS environment is recommended (the original docs target Linux; Windows is not officially supported in the current setup).
- PHP 8.4+ and Composer 2 if running without Docker.
- Docker and Docker Compose plugin if using containers.
- MySQL server and Redis server accessible with configured credentials.
- For Docker-based routing with hostnames, Traefik reverse proxy is referenced by the setup below.

Notes about Docker in this repo:
- docker-compose.yml references external Docker networks traefik and a Dockerfile that appears to be outside this repository path.

## Setup and Run
You can run the project in two ways: with Docker (recommended) or directly on your host.

### 1) Docker (recommended)
The provided docker-compose.yml expects:
- External networks: traefik
- A Docker build context and Dockerfile path that may be environment-specific and not included in this repo

Steps:
1. Create a .env file in the repo root with the variables listed above.
```bash
  cp .env.example .env
```
    At minimum set DB_* , REDIS_* , APP_RELEASE, YII_ENV, YII_DEBUG, and APP_HOST.

2. Ensure the external networks exist (if you use them):
```bash
  docker network create traefik (if not present)
```

3. Start Traefik reverse proxy if you plan to use hostname routing.

   Example traefik docker-compose.yml:
   ```yaml
   networks:
     proxy:
       external: true
     traefik:
       external: true
   services:
     traefik:
       image: traefik:latest
       container_name: traefik
       command:
         - "--log.level=DEBUG"
         - "--api.insecure=true"
         - "--providers.docker=true"
         - "--providers.docker.exposedbydefault=false"
         - "--entrypoints.web.address=:80"
       ports:
         - "80:80"
         - "8080:8080"
       labels:
         - traefik.enable=true
         - traefik.http.routers.traefik.rule=Host(`traefik.docker`)
         - traefik.http.routers.traefik.entrypoints=web
         - traefik.http.services.traefik.loadBalancer.server.port=8080
         - traefik.docker.network=traefik
       volumes:
         - "/var/run/docker.sock:/var/run/docker.sock:ro"
       networks:
         - proxy
         - traefik
       restart: always
   ```

4. Build and start containers via Makefile:
```bash
  make build
```
```bash
  make up
```

5. Install dependencies and run migrations:
```bash
  make start
```
If you use a local hostname, add it to /etc/hosts (example):
```bash
  echo "127.0.0.1 yii-test.docker" | sudo tee -a /etc/hosts
```
Then open http://yii-test.docker (or your APP_HOST).

### 2) Run without Docker
1. Ensure PHP 8.4+, Composer 2, MySQL, and Redis are installed and running.
2. Create .env in the repo root with at least DB_*, REDIS_*, APP_RELEASE, YII_ENV, YII_DEBUG variables.
3. Install dependencies: composer install
4. Apply DB migrations: ./yii migrate/up --interactive=0
5. Configure your web server to serve the web/ directory as document root. For PHP’s built-in server (dev only):
   - php -S 127.0.0.1:8080 -t web
6. Open http://127.0.0.1:8080


## Scripts and Tooling
Composer scripts:
- cs-check — PHP-CS-Fixer dry-run
- cs-fix — PHP-CS-Fixer apply fixes
- phpstan-check — Run PHPStan
- rector-check — Rector dry-run
- rector-fix — Rector apply fixes
- pre-commit — Runs cs-fix, phpstan-check, rector-fix

Assets:
- ./assets-publish.sh clears web/assets and runs: ./yii asset config/assets.php /dev/null
- If you add/remove assets, update config/assets.php accordingly.


## Testing
- Test runner: Codeception
- Config: codeception.yml (uses config/test.php)
- Run all tests: vendor/bin/codecept run

Note:
- The default test DB DSN is mysql:host=localhost;dbname=yii2basic_test (config/test_db.php). Create that database and grant permissions, or adjust the file to point to your test database.


## Project structure
- config/ — App configuration (web/console/test, DI container, logging, modules, routing)
- migrations/ — Yii DB migrations
- modules/
  - core/ — Core services (cache, DB transactions, Sentry, widgets, assets, RBAC integration)
  - site/ — Public site module
  - user/ — User module (auth, registration, services, repositories)
- tests/ — Codeception tests and helpers; tests/bin/yii for console under test env
- web/ — Public web root (index.php, published assets)
- vendor/ — Composer dependencies


## IDE/Xdebug
The project was used with PhpStorm and Xdebug. Example settings:
1) Settings -> Languages & Frameworks -> PHP -> Debug:
   - Xdebug ports: 9000, 9003
2) Settings -> Languages & Frameworks -> PHP -> Debug -> DBGp Proxy:
   - IDE key = PHPSTORM, Host = your APP_HOST, Port = 9003
3) Settings -> Languages & Frameworks -> PHP -> Servers:
   - Name = your APP_HOST, Host = your APP_HOST, Use path mappings
   - Project files absolute path on server = /var/www

Adjust according to your Docker/host setup.


## License
BSD-3-Clause (see composer.json). If you distribute binary or modified copies, include appropriate license notices.
