FROM php:7.2-apache

WORKDIR /var/www/html

RUN apt-get update \
  && apt-get install -y \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxslt1-dev \
    sendmail-bin \
    sendmail \
    unzip

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y libmcrypt-dev \
    && pecl install mcrypt-1.0.1 && docker-php-ext-enable mcrypt.so

RUN docker-php-ext-configure \
  gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

RUN docker-php-ext-install \
  dom \
  gd \
  intl \
  mbstring \
  pdo_mysql \
  xsl \
  zip \
  soap \
  bcmath

# Install MailHog
COPY ./mailhog.ini /usr/local/etc/php/conf.d/mailhog.ini

RUN curl -fSL "https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_linux_amd64" -o /usr/local/bin/mailhog \
    && chmod +x /usr/local/bin/mailhog

# Install xDebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN apt-get update -y && apt-get install -y \
    && pecl install xdebug  \
    && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | \
  php -- --install-dir=/usr/local/bin --filename=composer

#Copying magento sh installer
COPY ./install-magento-2.sh /install-magento-2.sh
RUN chmod +x /install-magento-2.sh

COPY ./conf/magento2.conf /etc/apache2/sites-available/magento2.conf
RUN ln -s /etc/apache2/sites-available/magento2.conf /etc/apache2/sites-enabled/magento2.conf

RUN a2enmod rewrite \
	&& a2enmod headers