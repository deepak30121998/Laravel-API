# Use an official PHP runtime as a parent image
FROM php:8.1.10-apache

# Install dependencies
RUN apt-get update -y && apt-get install -y \
    libzip-dev \
    unzip \
    openssl \
    git \
    && docker-php-ext-install zip


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory 
WORKDIR /app

# Copy the application files to the container
COPY . .

# Run composer install with autoloader optimization
RUN composer install --optimize-autoloader --no-dev

CMD php artisan serve --host=0.0.0.0
# Expose port 80 (the default for HTTP)
EXPOSE 80