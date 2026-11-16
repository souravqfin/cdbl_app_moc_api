FROM php:8.2.35-cli-alpine

# Install required dependencies and PHP extensions
RUN apk add --no-cache curl-dev \
    && docker-php-ext-install curl

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Render and Docker port
EXPOSE 8080

# Start PHP built-in server
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /var/www/html"]
