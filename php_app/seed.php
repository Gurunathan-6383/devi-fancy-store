<?php
require_once __DIR__ . '/src/Core/helpers.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Core/JWT.php';

echo "Seeding database...\n";

try {
    $db = \App\Core\Database::getInstance();

    $password = password_hash('admin@123', PASSWORD_BCRYPT);
    $db->query("INSERT IGNORE INTO users (email, password) VALUES (?, ?)", ['admin@gmail.com', $password]);
    echo "Admin user created (email: admin@gmail.com, password: admin@123)\n";

    $count = $db->fetch("SELECT COUNT(*) as count FROM categories")->count;
    if ($count == 0) {
        $defaultCategories = [
            'Bangles', 'Earrings', 'Pottu', 'Chains', 'Hair Clips',
            'Hair Pins', 'Anklets', 'Bracelets', 'Cosmetics', 'Gift Items', 'Kids Accessories'
        ];
        foreach ($defaultCategories as $name) {
            $slug = slugify($name);
            $db->query('INSERT IGNORE INTO categories (name, slug) VALUES (?, ?)', [$name, $slug]);
        }
        echo "Default categories created\n";
    }

    $settingsCount = $db->fetch("SELECT COUNT(*) as count FROM settings")->count;
    if ($settingsCount == 0) {
        $db->query(
            'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)',
            ['store_name', 'Devi Fancy Store', 'phone', '', 'email', '', 'address', '', 'theme', 'light']
        );
        echo "Default settings created\n";
    }

    echo "Seed completed!\n";
} catch (Exception $e) {
    echo "Seed failed: " . $e->getMessage() . "\n";
    exit(1);
}
