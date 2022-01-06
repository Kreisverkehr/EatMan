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
RUN rm -rf composer*
COPY ./wwwroot/ .
RUN mv sys/settings.docker.php sys/settings.php

FROM php:apache
ENV WAIT_VERSION 2.7.2
ADD https://github.com/ufoscout/docker-compose-wait/releases/download/$WAIT_VERSION/wait /wait
RUN chmod +x /wait
RUN apt update && apt install -y \
    default-mysql-client-core \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli
COPY ./setup/createdb.sql /opt
COPY ./docker/eatman-start.sh /opt
COPY --from=build /var/www/html /var/www/html
ENV EM_DBHOST=mysql EM_DBUSER=EatMan EM_DBPASS=EatManPass
WORKDIR /opt
ENTRYPOINT [ "/opt/eatman-start.sh" ]