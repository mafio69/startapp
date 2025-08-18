FROM php:8.3-fpm

# Install packages
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    cron \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    opcache

# Install Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Copy configurations
COPY ./nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./nginx/default.conf /etc/nginx/sites-available/default
COPY ./php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY ./php/php.ini /usr/local/etc/php/php.ini
COPY ./php/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY ./supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create directories and set permissions
RUN mkdir -p /var/www/html /var/log/app /var/tmp /var/cache /var/run/php \
    && chown -R www-data:www-data /var/www /var/log /var/tmp /var/cache /var/run/php \
    && chmod -R 755 /var/www /var/log /var/tmp /var/cache /var/run/php \
    usermod -a -G adm www-data

# Enable shell for www-data
RUN usermod --shell /bin/bash www-data

# Prepare crontab file
COPY ./cron/crontab /tmp/crontab
RUN chmod 0644 /tmp/crontab

# Install crontab for www-data user
RUN crontab -u www-data /tmp/crontab && rm /tmp/crontab

# Configure cron logs
RUN touch /var/log/cron.log && \
    chown www-data:www-data /var/log/cron.log && \
    chmod 666 /var/log/cron.log

# Clean default PHP-FPM config pool
RUN rm -rf /usr/local/etc/php-fpm.d/*

WORKDIR /var/www/html

EXPOSE 80 9003

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
