FROM php:8.4-fpm AS builder

ENV \
    DEBIAN_FRONTEND=noninteractive \
    PHP_EXTENSION_DIR="/usr/local/lib/php/extensions/ext" \
    COMPOSER_HOME="/usr/local/composer" \
    COMPOSER_MEMORY_LIMIT=-1 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    NGINX_ARCHIVE_KEYRING="/usr/share/keyrings/nginx-archive-keyring.gpg" \
    LANG="en_US.UTF-8" \
    LANGUAGE="en_US.UTF-8" \
    LC_LANG="en_US.UTF-8" \
    LC_ALL="en_US.UTF-8"

RUN apt update &&  \
    apt upgrade -y &&  \
    apt install -y \
    ca-certificates \
    curl \
    debian-archive-keyring \
    git \
    gnupg2 \
    lsb-release \
    nano \
    procps \
    unzip

RUN NO_ZTS_PHP_EXTENSION_DIR=$(php -r "echo ini_get('extension_dir');") \
    && mkdir ${PHP_EXTENSION_DIR} \
    && mv ${NO_ZTS_PHP_EXTENSION_DIR}/*.so ${PHP_EXTENSION_DIR} \
    && rmdir ${NO_ZTS_PHP_EXTENSION_DIR} \
    && ln -s ${PHP_EXTENSION_DIR} ${NO_ZTS_PHP_EXTENSION_DIR}

# Install system dependencies and PHP extensions
COPY --from=ghcr.io/mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    @composer-2 \
    bcmath \
    calendar \
    gd \
    gettext \
    intl \
    ldap \
    mbstring \
    opcache \
    pcntl \
    pdo_pgsql \
    redis \
    xdebug \
    zip

RUN mkdir -p ${COMPOSER_HOME} \
    /usr/local/var/run \
    /usr/local/var/log

# nginx
RUN mkdir -p ~/.gnupg/ && chmod 700 ~/.gnupg
RUN curl https://nginx.org/keys/nginx_signing.key | gpg --dearmor | tee ${NGINX_ARCHIVE_KEYRING} >/dev/null \
    && gpg --dry-run --quiet --no-keyring --import --import-options import-show ${NGINX_ARCHIVE_KEYRING} \
    && echo "deb [signed-by=${NGINX_ARCHIVE_KEYRING}] http://nginx.org/packages/debian `lsb_release -cs` nginx" | tee /etc/apt/sources.list.d/nginx.list \
    && echo "Package: *\nPin: origin nginx.org\nPin: release o=nginx\nPin-Priority: 900\n" | tee /etc/apt/preferences.d/99nginx

RUN apt update \
    && apt install -y nginx \
    && rm -f /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/conf.d/default.conf \
    && mkdir -p /var/cache/nginx

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

## locales
RUN apt update \
    && apt install -y \
           locales \
    && /bin/sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen \
    && /bin/sed -i -e 's/# ru_RU.UTF-8 UTF-8/ru_RU.UTF-8 UTF-8/' /etc/locale.gen \
    && locale-gen

ARG USER_NAME=user
ARG USER_ID=1000
ARG GROUP_NAME=group
ARG GROUP_ID=1000

RUN if getent group ${GROUP_ID}; then \
      group_name=$(getent group ${GROUP_ID} | cut -d: -f1); \
      useradd -m -u ${USER_ID} -g ${GROUP_ID} -d /home/${USER_NAME} -s /bin/bash ${USER_NAME}; \
    else \
      groupadd -g ${GROUP_ID} ${GROUP_NAME}; \
      useradd -m -u ${USER_ID} -g ${GROUP_ID} -d /home/${USER_NAME} -s /bin/bash ${USER_NAME}; \
      group_name=${GROUP_NAME}; \
    fi

RUN chown -R ${USER_NAME}:${GROUP_NAME} \
       /usr/local/var/run \
       /usr/local/var/log \
       /var/cache \
       ${COMPOSER_HOME}

# cleanup
RUN apt remove -y ${PHPIZE_DEPS} \
        gnupg2 \
    && apt autoclean \
    && apt autoremove -y \
    && rm -rf /tmp/* \
        /var/cache/*/* \
        /var/lib/apt/lists/* \
        /usr/local/etc/php-fpm.d/*

USER root

WORKDIR /var/www

CMD ["php-fpm", "-F", "-R"]
