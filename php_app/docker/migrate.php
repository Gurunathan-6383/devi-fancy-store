<?php
/**
 * Render deployment migration script.
 * Called by docker-entrypoint.sh:  php /var/www/html/docker/migrate.php
 *
 * Reads DB config from environment variables (set by Render).
 * Creates the database if it doesn't exist, imports schema, runs seed.
 */

$host     = getenv('DB_HOST')     ?: 'localhost';
$user     = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$dbName   = getenv('DB_NAME')     ?: 'devi_fancy_store';

echo "DB host: {$host}\n";
echo "DB name: {$dbName}\n";

// Connect without selecting a database
try {
    $pdo = new PDO("mysql:host={$host};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    echo "ERROR: Cannot connect to database: " . $e->getMessage() . "\n";
    exit(1);
}

// Create database if it doesn't exist
$pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE `{$dbName}`");

echo "Database '{$dbName}' ready.\n";

// Import schema
$schemaFile = __DIR__ . '/../database/schema.sql';
if (file_exists($schemaFile)) {
    $sql = file_get_contents($schemaFile);

    // Strip the CREATE DATABASE and USE statements (we already handled them)
    $sql = preg_replace('/CREATE DATABASE[^;]+;/i', '', $sql);
    $sql = preg_replace('/USE\s+`?[a-zA-Z0-9_]+`?\s*;/i', '', $sql);

    // Split into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    $imported = 0;
    $skipped  = 0;
    foreach ($statements as $stmt) {
        if ($stmt === '') continue;
        try {
            $pdo->exec($stmt);
            $imported++;
        } catch (PDOException $e) {
            // Table already exists or duplicate key — expected on re-deploys
            $skipped++;
        }
    }
    echo "Schema imported: {$imported} statements executed, {$skipped} skipped (already exist).\n";
} else {
    echo "WARNING: schema.sql not found at {$schemaFile}\n";
}

// Run seed (uses the project's own helpers/config)
$seedFile = __DIR__ . '/../seed.php';
if (file_exists($seedFile)) {
    echo "Running seed...\n";
    chdir(__DIR__ . '/..');
    // Suppress warnings from INSERT IGNORE duplicates
    $previousLevel = error_reporting();
    error_reporting(E_ERROR);
    include $seedFile;
    error_reporting($previousLevel);
    echo "Seed completed.\n";
} else {
    echo "WARNING: seed.php not found\n";
}

echo "Migration finished.\n";
