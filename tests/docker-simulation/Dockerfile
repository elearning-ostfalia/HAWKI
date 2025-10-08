FROM php:8.4-apache



# COPY ./reverb.service /etc/systemd/system
# COPY ./laravel-worker.service /etc/systemd/system



RUN echo "Europe/Berlin" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN  apt-get update && apt-get install -y ca-certificates gnupg
# install node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

RUN apt-get update && apt-get upgrade -y && apt-get install -y nodejs

# install for debugging (remove when running)
RUN apt-get update && apt-get upgrade -y && apt-get install -y net-tools

#RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
#RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable SSL module, configure Apache for PHP support, and enable our SSL site configuration
RUN a2enmod rewrite && \
    a2enmod headers && \
    a2enmod expires && \
    a2enmod ssl && \
    a2enmod proxy && \
    a2enmod proxy_http && \
    a2enmod proxy_wstunnel


RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl


RUN apt-get update && apt-get install -y libzip-dev zip libicu-dev libpq-dev && docker-php-ext-install zip

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pdo_pgsql pgsql


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# xdebug
#RUN pecl install xdebug && docker-php-ext-enable xdebug
#RUN echo 'zend_extension=xdebug' >> /usr/local/etc/php/php.ini
#RUN echo 'xdebug.mode=develop,debug' >> /usr/local/etc/php/php.ini
#RUN echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/php.ini
#RUN echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/php.ini
#RUN echo 'session.save_path = "/tmp"' >> /usr/local/etc/php/php.ini



# OLAF Zeugs

# Copy SSL certificate and key
COPY olaf.crt /etc/ssl/certs/olaf.crt
COPY olaf.key /etc/ssl/private/olaf.key

# Copy the custom Apache virtual host config
COPY ./olaf-httpd-vhosts.conf /etc/apache2/sites-available/olaf-ssl.conf

RUN a2dissite 000-default default-ssl && a2ensite olaf-ssl


# run entrypoint.sh with installation script
ENTRYPOINT ["/var/www/html/tests/docker-simulation/install.sh"]

