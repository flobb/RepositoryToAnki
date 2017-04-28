FROM php:7.1-cli

RUN buildDeps=" \
    zlib1g-dev \
    " \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        $buildDeps \
        ssl-cert \
        git \
    && docker-php-ext-install opcache zip \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false $buildDeps \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# PHP conf
COPY php.ini $PHP_INI_DIR/conf.d/z_docker.ini

# Add composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Directory of the application
WORKDIR /www

# Override the image ENTRYPOINT to add some logic
ADD entrypoint.sh /sbin/entrypoint
RUN chmod +x /sbin/entrypoint
ENTRYPOINT ["entrypoint"]
