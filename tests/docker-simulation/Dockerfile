FROM php:8.2-apache


# Copy SSL certificate and key
COPY olaf.crt /etc/ssl/certs/olaf.crt
COPY olaf.key /etc/ssl/private/olaf.key

# Copy the custom Apache virtual host config
COPY ./olaf-httpd-vhosts.conf /etc/apache2/sites-available/olaf-ssl.conf

RUN echo "Europe/Berlin" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN  apt-get update && apt-get install -y ca-certificates gnupg
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -

RUN apt-get update && apt-get upgrade -y && apt-get install -y nodejs

#RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
#RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite && a2enmod headers && a2enmod expires && a2enmod ssl

RUN a2dissite 000-default default-ssl && a2ensite olaf-ssl
# RUN a2ensite olaf-ssl


# Enable SSL module, configure Apache for PHP support, and enable our SSL site configuration
#RUN a2enmod ssl && \
#    a2enmod rewrite && \
#    a2dissite 000-default default-ssl && \
#    a2ensite my-ssl

RUN apt-get update && apt-get install -y libzip-dev zip libicu-dev libpq-dev && docker-php-ext-install zip

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pdo_pgsql pgsql


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# OLAF Zeugs

# run entrypoint.sh with installation script
ENTRYPOINT ["/var/www/html/tests/docker-simulation/install.sh"]

