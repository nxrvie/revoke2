# CyberShield — déploiement Render / Docker
# L'application PHP tourne avec Apache et utilise database.sqlite fournie.
FROM php:8.2-apache

# PDO SQLite (mode par défaut) + PDO MySQL (si une base externe est fournie)
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Le dossier cybershield/ devient la racine web
COPY --chown=www-data:www-data cybershield/ /var/www/html/

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000
CMD ["/usr/local/bin/start.sh"]
