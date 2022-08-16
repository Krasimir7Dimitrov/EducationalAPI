FROM php:7.4-fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS

RUN apk add --no-cache libxml2-dev 

RUN apk add --no-cache git

RUN docker-php-ext-install pdo pdo_mysql soap mysqli

RUN addgroup -g 1000 mvc && adduser -G mvc -g mvc -s /bin/sh -D mvc
 
USER mvc

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

ENTRYPOINT [ "composer" ]
