FROM ubuntu:24.04
ARG DEBIAN_FRONTEND=noninteractive

RUN  apt update -y && apt upgrade -y
RUN apt install -y apt-utils git rsync nano vim unzip curl wget software-properties-common git mariadb-client
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php

# Instalo software requerido por moodle
RUN apt-get install -y apache2 php8.3 php8.3-iconv php8.3-mbstring php8.3-curl php8.3-tokenizer php8.3-xmlrpc php8.3-soap php8.3-ctype php8.3 php8.3-zip php8.3-gd php8.3-simplexml php8.3-dom php8.3-xml php8.3-intl php8.3-mysql libapache2-mod-php8.3 zip unzip git

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
RUN rm /etc/apache2/sites-enabled/000-default.conf

# Configuro variable de PHP para que se pueda instalar correctamente
RUN echo max_input_vars=6000 >> /etc/php/8.3/apache2/php.ini

# Descargar y descomprimir archivos web
RUN cd /var/www && wget https://packaging.moodle.org/stable405/moodle-latest-405.zip
RUN cd /var/www && unzip moodle-latest-405.zip && rm moodle-latest-405.zip
RUN chown www-data:www-data -R /var/www/moodle
COPY moodle.conf /etc/apache2/sites-enabled/moodle.conf

# Creo la ruta de los datos de moodle y le doy permisos
RUN mkdir /var/www/moodledata
RUN chown -R www-data:www-data /var/www/moodledata/

# Activar opciones de apache y reiniciar servicio
RUN a2enmod rewrite headers
RUN service apache2 restart
WORKDIR /var/www/moodle