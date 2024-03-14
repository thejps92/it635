# Uses the PHP 8.2 Apache base image
FROM php:8.2-apache

# Enables Apache modules
RUN a2enmod rewrite

# Updates the package list, installs the PostgreSQL development libraries, and installs the PHP extensions required for PostgreSQL connectivity
RUN apt update && \
    apt install -y libpq-dev && \
    docker-php-ext-install pgsql pdo_pgsql pdo