ARG PHP_VERSION=7.4

FROM wordpress:php${PHP_VERSION:-7.4}-fpm-alpine
COPY php.ini /usr/local/etc/php/conf.d/gh-custom.ini
COPY ./docker-entrypoint.sh /docker-entrypoint.sh
RUN apk add --update --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    pecl install xdebug-3.1.6 && \
    docker-php-ext-enable xdebug && \
    apk del .build-deps && \
    chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["php-fpm"]
