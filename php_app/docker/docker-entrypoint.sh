#!/bin/bash
set -e

echo "Starting Devi Fancy Store..."

# Run database migrations and seed if DB is configured
if [ -n "$DB_HOST" ]; then
    echo "Database configured, running schema migration..."
    
    # Wait for database to be ready
    until php -r "
        try {
            new PDO('mysql:host=${DB_HOST};charset=utf8mb4', '${DB_USER:-root}', '${DB_PASSWORD:-}', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            exit(0);
        } catch (PDOException \$e) {
            exit(1);
        }
    " 2>/dev/null; do
        echo "Waiting for database..."
        sleep 2
    done
    
    echo "Database is ready!"
    
    # Import schema via PHP PDO
    if [ -f /var/www/html/database/schema.sql ]; then
        echo "Importing schema..."
        php -r "
            \$sql = file_get_contents('/var/www/html/database/schema.sql');
            \$stmts = array_filter(array_map('trim', explode(';', \$sql)));
            \$pdo = new PDO('mysql:host=${DB_HOST};charset=utf8mb4', '${DB_USER:-root}', '${DB_PASSWORD:-}', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            \$pdo->exec('CREATE DATABASE IF NOT EXISTS ${DB_NAME:-devi_fancy_store} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            \$pdo->exec('USE ${DB_NAME:-devi_fancy_store}');
            foreach (\$stmts as \$stmt) {
                if (!empty(\$stmt) && \$stmt !== 'USE ${DB_NAME:-devi_fancy_store}') {
                    try { \$pdo->exec(\$stmt); } catch (Exception \$e) {}
                }
            }
            echo \"Schema imported successfully\n\";
        " 2>&1 || echo "Schema import completed (tables may already exist)"
    fi
    
    # Run seed
    echo "Running seed..."
    cd /var/www/html && php seed.php 2>&1 || echo "Seed completed"
fi

echo "Starting Apache..."
exec apache2-foreground
