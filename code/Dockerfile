ARG PHP_VERSION=7.4
ARG NODE_VERSION=16

# Step 1 -- Composer
FROM php:${PHP_VERSION:-7.4}-cli-alpine AS builder

ARG ACF_PRO_KEY
ENV ACF_PRO_KEY=${ACF_PRO_KEY}

WORKDIR /app

COPY --from=composer:2.3.3 /usr/bin/composer /usr/local/bin
COPY . /app

RUN apk add --update git && \
    composer install --no-dev

# Step 2 -- Gulp
FROM node:${NODE_VERSION:-10}-slim AS theme

ARG FONTAWESOME_NPM_AUTH_TOKEN
ENV FONTAWESOME_NPM_AUTH_TOKEN=${FONTAWESOME_NPM_AUTH_TOKEN}

RUN apt-get update && apt-get install -y python build-essential
WORKDIR /theme

COPY --from=builder /app/web/app/themes/idc /theme

RUN apt-get update && \
    apt-get install -y git libnotify-bin && \
    npm config set '@fortawesome:registry' 'https://npm.fontawesome.com/' && \
    npm config set '//npm.fontawesome.com/:_authToken' "${FONTAWESOME_NPM_AUTH_TOKEN}" && \
    npm i -g bower gulp@4 && \
    yarn install && \
    bower install && \
    yarn run build

# Step 3 -- PHP FPM
FROM wordpress:php${PHP_VERSION:-7.4}-fpm-alpine AS final

COPY --from=builder --chown=www-data:www-data /app /var/www/html
COPY --from=theme --chown=www-data:www-data /theme/dist /var/www/html/web/app/themes/idc/dist

RUN rm -rf /var/www/html/wp-content

ENTRYPOINT ["php-fpm"]
