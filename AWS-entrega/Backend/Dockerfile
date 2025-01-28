FROM ubuntu:24.04
ARG DEBIAN_FRONTEND=noninteractive
RUN  apt update --fix-missing -y && apt upgrade -y
ENV DATABASE_URL=mysql://4vtest:abcd1234@10.10.5.45:3306/bbddentrega?serverVersion=10.6.18-MariaDB&charset=utf8mb4
RUN apt install -y apt-utils git rsync nano vim unzip curl wget software-properties-common git
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php

RUN apt-get install -y sudo apache2 php8.3 php8.3-common php8.3-cli php8.3-gd php8.3-mysql php8.3-mbstring php8.3-bcmath php8.3-xml php8.3-fpm php8.3-curl php8.3-zip libapache2-mod-php8.3 zip unzip

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
RUN rm /var/www/html/*
RUN rm /etc/apache2/sites-enabled/000-default.conf
COPY backend.conf /etc/apache2/sites-enabled/backend.conf

COPY www/. /var/www/html
RUN chown www-data:www-data -R /var/www/html/*

RUN a2enmod rewrite headers
RUN service apache2 restart
WORKDIR /var/www/html/