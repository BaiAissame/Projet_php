#!/bin/sh

# Attendre que MariaDB soit prêt
echo "⌛ Attente de MariaDB..."
until nc -z -v -w30 mariadb 3306
do
  echo "⏳ En attente de MariaDB..."
  sleep 5
done
echo "✅ MariaDB est prêt !"

# Exécuter les migrations
echo "🚀 Exécution des migrations..."
php /home/php/db/migrate.php

# Lancer PHP-FPM (ou autre service PHP)
exec "$@"
