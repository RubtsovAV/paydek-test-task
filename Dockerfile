# laravel5
FROM php:7.1-apache
MAINTAINER RubtsovAV@gmail.com

# Installing dependencies
RUN apt-get update && apt-get upgrade -y && \
    apt-get -y install --no-install-recommends \
        logrotate \
        cron \
    	supervisor \
        bzip2 \
        freetds-dev \
        git \
        libfontconfig \
        libfreetype6-dev \
        libicu-dev \
        libjpeg-dev \
        libldap2-dev \
        libmcrypt-dev \
        default-libmysqlclient-dev \
        libpng-dev \
        libwebp-dev \
        libxml2-dev \
        zlib1g-dev

# Configuring extensions to compile
RUN docker-php-ext-configure gd \
    --with-jpeg-dir=lib/x86_64-linux-gnu \
    --with-freetype-dir=lib/x86_64-linux-gnu \
    --with-webp-dir=lib/x86_64-linux-gnu/

# Compiling and installing extensions
RUN docker-php-ext-install \
    gd \
    exif \
    intl \
    mcrypt \
    mbstring \
    pdo_mysql \
    zip \
    pcntl \
    posix \
    opcache \
    sysvsem

# Install Xdebug
RUN git clone git://github.com/xdebug/xdebug.git \
    && ( \
    cd xdebug \
    && phpize \
    && ./configure --enable-xdebug \
    && make -j$(nproc) \
    && make install \
    ) \
    && rm -r xdebug

# Clean cache of repository
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install composer && global laravel/installer
ENV COMPOSER_HOME /root/.composer
ENV PATH /root/.composer/vendor/bin:$PATH
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD php.ini /usr/local/etc/php/conf.d/zzz_overrides.ini

ADD app /app
RUN cd /app && composer install

# Change of the DOCUMENT_ROOT folder
RUN rm -rf /var/www/html && \
	ln -s /app/public /var/www/html

ADD cron.d /root/cron.d
ADD logrotate.d /root/logrotate.d
ADD supervisor /etc/supervisor/conf.d

ADD run.sh /root/run.sh
RUN chmod 700 /root/run.sh

WORKDIR /app

CMD ["/root/run.sh"]
