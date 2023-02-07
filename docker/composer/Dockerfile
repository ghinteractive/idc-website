ARG PHP_VERSION=7.4

FROM php:${PHP_VERSION:-7.4}-cli-alpine

WORKDIR /app
COPY --from=composer:2.3.3 /usr/bin/composer /usr/local/bin
RUN apk add --update git

ENTRYPOINT ["composer"]