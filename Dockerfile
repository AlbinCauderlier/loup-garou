FROM php:7.3-apache 

RUN apt-get update && \
    apt-get install -y

RUN apt-get update -y && \ 
	# apt-get install -y sendmail libpng-dev redis-server &&  \
	apt-get install -y libpng-dev &&  \
	apt-get clean 
	# && \
	# apt autoremove

# Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install gd

# Enable Apache modules
RUN a2enmod rewrite headers expires deflate remoteip

# PHP Extensions
RUN docker-php-ext-install -j$(nproc) opcache pdo_mysql
# ADD conf/php.ini /usr/local/etc/php/conf.d/app.ini

# Apache configuration
ADD conf/vhost.conf /etc/apache2/sites-available/000-default.conf
ADD conf/apache.conf /etc/apache2/conf-available/z-app.conf
RUN a2enconf z-app

# EXPOSE 80 8080
