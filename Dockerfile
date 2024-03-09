FROM registry.eofactory.ai:5000/laravel-php82

WORKDIR /var/www/

COPY composer.json /var/www/composer.json

RUN composer install --no-scripts --no-autoloader

COPY apache.conf /etc/apache2/sites-enabled/000-default.conf

COPY . .

COPY docker.env .env

RUN chmod +x docker-start.sh

RUN a2enmod rewrite && a2enmod ssl && a2enmod headers && a2enmod proxy_http

EXPOSE 80 443


ENTRYPOINT ["/var/www/docker-start.sh"]
