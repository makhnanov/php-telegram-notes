FROM php:8.1.0RC6-cli-alpine3.14
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apk add bash
RUN apk --update add --virtual build-dependencies build-base openssl-dev autoconf \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb \
  && apk del build-dependencies build-base openssl-dev autoconf \
  && rm -rf /var/cache/apk/*
WORKDIR /app