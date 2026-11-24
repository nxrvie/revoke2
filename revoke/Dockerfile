# CyberShield — déploiement Render / Docker
# L'application PHP tourne avec Apache et utilise database.sqlite fournie.
FROM php:8.2-apache

# libsqlite3-dev fournit sqlite3.pc, requis pour compiler pdo_sqlite
RUN apt-get update && apt-get install -y --no-install-recommends \
        libsqlite3-dev pkg-config \
    && rm -rf /var/lib/apt/lists/*

# PDO SQLite (mode par défaut) + PDO MySQL (si une base externe est fournie)
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Base SQLite hors de la racine web (pas téléchargeable par un visiteur)
RUN mkdir -p /var/www/data && chown www-data:www-data /var/www/data
COPY --chown=www-data:www-data cybershield/database.sqlite /var/www/data/database.sqlite
ENV DB_SQLITE_FILE=/var/www/data/database.sqlite

# Le dossier cybershield/ devient la racine web
COPY --chown=www-data:www-data cybershield/ /var/www/html/

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000
CMD ["/usr/local/bin/start.sh"]
