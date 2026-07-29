#!/bin/bash
set -e

echo "=== Devi Fancy Store — Deploy ==="

# Only run migration if DB_HOST is provided (Render sets this via database link)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database at $DB_HOST..."
    for i in $(seq 1 30); do
        if php -r "new PDO('mysql:host=$DB_HOST;charset=utf8mb4', '${DB_USER:-root}', '${DB_PASSWORD:-}');" 2>/dev/null; then
            echo "Database is ready."
            break
        fi
        echo "  retry $i/30..."
        sleep 2
    done

    php /var/www/html/docker/migrate.php
fi

echo "Starting Apache..."
exec apache2-foreground
