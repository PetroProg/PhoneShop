FROM docker.io/library/php:7.4-apache
WORKDIR /app
COPY . .
RUN docker-php-ext-install mysqli pdo pdo_mysql 

