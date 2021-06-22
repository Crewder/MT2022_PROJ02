FROM php:7.4-apache

RUN apt-get -y update --fix-missing
RUN apt-get upgrade -y

# Install useful tools
RUN apt-get -y install apt-utils nano wget dialog curl software-properties-common gnupg
RUN apt-get -y install sudo

# Install useful cron
RUN apt-get -y install cron

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Run the command on container startup
CMD cron && tail -f /var/log/cron.log

# Install important libraries
RUN apt-get -y install --fix-missing apt-utils build-essential git curl libcurl4 libcurl3-dev zip openssl libpng-dev libjpeg-dev libsodium-dev

RUN docker-php-ext-install pdo pdo_mysql

RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-configure sodium
RUN docker-php-ext-install sodium

RUN docker-php-ext-install sockets

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG USER=docker
ARG UID=1000
ARG GID=1000
ARG PW=password

RUN useradd -m ${USER} --uid=${UID} && echo "${USER}:${PW}" | \
      chpasswd

USER ${USER}

USER root

# Enable apache modules
RUN a2enmod rewrite headers
RUN a2enmod ssl

CMD ( cron -f & ) && apachectl -D FOREGROUND
