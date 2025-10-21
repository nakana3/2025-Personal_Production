FROM php:8.2-apache

# 必要な拡張機能のインストール
# pdo_mysql はDB接続に必須
RUN docker-php-ext-install pdo pdo_mysql

# Composerのインストール (Guzzleなどのライブラリ管理用)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ApacheのRewriteモジュールを有効化
RUN a2enmod rewrite

WORKDIR /var/www/html


# CMD ["apache2-foreground"]