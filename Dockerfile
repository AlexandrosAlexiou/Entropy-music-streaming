FROM php:7.3.11-apache
RUN docker-php-ext-install mysqli
