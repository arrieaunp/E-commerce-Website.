ARG PHP_VERSION=8
FROM php:${PHP_VERSION}-apache
RUN docker-php-ext-install mysqli
RUN groupadd -r apache && useradd -r -g apache apache-user
USER apache-user
COPY . /var/www/html/