FROM --platform=linux/amd64 dunglas/frankenphp
#FROM dunglas/frankenphp

# add additional extensions here:
RUN install-php-extensions \
	pdo_mysql \
	gd \
	intl \
    imap \
    bcmath \
    redis \
    curl \
    exif \
    hash \
    iconv \
    json \
    mbstring \
    mysqli \
    mysqlnd \
    pcntl \
    pcre \
    xml \
    libxml \
    zlib \
	zip

RUN apt update && apt install -y supervisor

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY . /app

COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN composer update --no-dev -o  -n -W

RUN php artisan storage:link

EXPOSE 80 443

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]