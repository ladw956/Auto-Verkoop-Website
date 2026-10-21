FROM php:8.4-cli-alpine
RUN apk add --no-cache sqlite-dev \
    && docker-php-ext-install pdo_sqlite
WORKDIR /app
COPY . .
ENV PORT=10000 \
    DB_DRIVER=sqlite \
    DB_PATH=/tmp/autoverkoop.sqlite
EXPOSE 10000
CMD ["sh", "-c", "php render/init_db.php && php -S 0.0.0.0:${PORT} -t /app"]
