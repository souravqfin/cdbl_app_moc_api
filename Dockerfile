FROM php:8.2.35-cli-alpine

RUN apk add --no-cache curl-dev \
    && docker-php-ext-install curl

WORKDIR /var/www/html

COPY index.php .

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]
