#!/bin/sh
set -e

# Render fournit le port à écouter dans PORT (10000 par défaut).
PORT="${PORT:-10000}"

echo "Listen ${PORT}" > /etc/apache2/ports.conf
if [ -f /etc/apache2/sites-available/000-default.conf ]; then
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

echo "CyberShield démarré sur le port ${PORT} (base : sqlite)"
exec apache2-foreground
