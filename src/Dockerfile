FROM php:apache as build

RUN apt update && apt install -y \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html
COPY ./wwwroot/composer.json .
COPY ./wwwroot/composer.lock .
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN php composer.phar install
COPY ./wwwroot/ .
RUN rm -rf composer*
RUN mv sys/settings.docker.php sys/settings.php

FROM php:apache
RUN apt update && apt install -y \
    default-mysql-client-core \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli
COPY ./setup/createdb.sql /opt/
COPY ./docker/eatman-start.sh /opt/
COPY ./docker/wait-for-it.sh /opt/
RUN chmod -R 777 /opt
COPY --from=build /var/www/html /var/www/html
ENV EM_DBHOST=mysql EM_DBUSER=EatMan EM_DBPASS=EatManPass
WORKDIR /opt
ENTRYPOINT [ "/opt/eatman-start.sh" ]