FROM php:8.2-fpm-alpine
WORKDIR /app
COPY . .
RUN docker-php-ext-install mysqli pdo pdo_mysql 

