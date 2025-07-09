FROM php:8.3-apache

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql


# Copy your app files into Apache's web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html
