# Use the official PHP image with Apache as the web server
FROM php:8.2.11-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Install a specific version of Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.5.4 --install-dir=/usr/local/bin --filename=composer

# Set the document root for Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Enable Apache modules
RUN a2enmod rewrite

# Create a non-root user for running Composer and the application
RUN useradd -ms /bin/bash laravel

# Set the working directory
WORKDIR /var/www/html

# Copy the Laravel application code into the container
COPY . .

# Change ownership of the storage and bootstrap/cache directories
RUN chown -R laravel:laravel /var/www/html/storage /var/www/html/bootstrap/cache

# Change permissions of the storage and bootstrap/cache directories
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Switch to the non-root user
USER laravel

# Expose port 80
EXPOSE 80

# Healthcheck to ensure Apache is running
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 CMD curl -f http://localhost/ || exit 1

# The CMD instruction specifies the command to run when the container starts
CMD ["apache2-foreground"]
