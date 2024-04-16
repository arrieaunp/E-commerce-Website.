FROM php:8.2-apache
WORKDIR /var/www/html
RUN apt-get update -y && apt-get install -y libmariadb-dev 
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY ./ /var/www/html/