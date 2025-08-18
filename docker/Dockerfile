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
    logrotate \
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

# Install Composer
RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && \
    HASH=$(curl -sS https://composer.github.io/installer.sig) && \
    php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('/tmp/composer-setup.php'); } echo PHP_EOL;" && \
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm /tmp/composer-setup.php

# Install Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Copy configurations
COPY ./nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./nginx/default.conf /etc/nginx/sites-available/default
COPY ./php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY ./php/php.ini /usr/local/etc/php/php.ini
COPY ./supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create directories and set permissions
RUN mkdir -p /var/www/html /var/log/app /var/tmp /var/cache /var/run/php && \
    chown -R www-data:www-data /var/www && \
    chown -R www-data:www-data /var/log && \
    chown -R www-data:www-data /var/tmp && \
    chown -R www-data:www-data /var/cache && \
    chown -R www-data:www-data /var/run/php && \
    chmod -R 755 /var/www && \
    chmod -R 755 /var/log && \
    chmod -R 755 /var/tmp && \
    chmod -R 755 /var/cache && \
    chmod -R 755 /var/run/php && \
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

# Final fix PHP-FPM configuration (overwrite default config to avoid /proc/self/fd/2 error)
RUN rm -rf /usr/local/etc/php-fpm.conf /usr/local/etc/php-fpm.d/* && \
    echo "[global]" > /usr/local/etc/php-fpm.conf && \
    echo "error_log = /dev/null" >> /usr/local/etc/php-fpm.conf && \
    echo "daemonize = no" >> /usr/local/etc/php-fpm.conf && \
    echo "" >> /usr/local/etc/php-fpm.conf && \
    echo "[www]" >> /usr/local/etc/php-fpm.conf && \
    echo "user = www-data" >> /usr/local/etc/php-fpm.conf && \
    echo "group = www-data" >> /usr/local/etc/php-fpm.conf && \
    echo "listen = 127.0.0.1:9000" >> /usr/local/etc/php-fpm.conf && \
    echo "pm = dynamic" >> /usr/local/etc/php-fpm.conf && \
    echo "pm.max_children = 5" >> /usr/local/etc/php-fpm.conf && \
    echo "pm.start_servers = 2" >> /usr/local/etc/php-fpm.conf && \
    echo "pm.min_spare_servers = 1" >> /usr/local/etc/php-fpm.conf && \
    echo "pm.max_spare_servers = 3" >> /usr/local/etc/php-fpm.conf

WORKDIR /var/www/html

EXPOSE 80 9003

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
