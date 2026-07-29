const bcrypt = require('bcryptjs');
const mysql = require('mysql2/promise');
require('dotenv').config();

async function seed() {
  const connection = await mysql.createConnection({
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'devi_fancy_store',
  });

  console.log('Connected to database...');

  const adminPassword = await bcrypt.hash('admin@123', 10);

  await connection.execute(
    'INSERT IGNORE INTO users (email, password) VALUES (?, ?)',
    ['admin@gmail.com', adminPassword]
  );
  console.log('Admin user created (email: admin@gmail.com, password: admin@123)');

  // Check if default categories exist
  const [existing] = await connection.execute('SELECT COUNT(*) as count FROM categories');
  if (existing[0].count === 0) {
    const defaultCategories = [
      'Bangles', 'Earrings', 'Pottu', 'Chains', 'Hair Clips',
      'Hair Pins', 'Anklets', 'Bracelets', 'Cosmetics', 'Gift Items', 'Kids Accessories'
    ];
    for (const name of defaultCategories) {
      const slug = name.toLowerCase().replace(/\s+/g, '-');
      await connection.execute('INSERT INTO categories (name, slug) VALUES (?, ?)', [name, slug]);
    }
    console.log('Default categories created');
  }

  // Check if default settings exist
  const [settingsCount] = await connection.execute('SELECT COUNT(*) as count FROM settings');
  if (settingsCount[0].count === 0) {
    await connection.execute(
      'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)',
      ['store_name', 'Devi Fancy Store', 'phone', '', 'email', '', 'address', '', 'theme', 'light']
    );
    console.log('Default settings created');
  }

  console.log('Seed completed!');
  await connection.end();
  process.exit(0);
}

seed().catch(err => {
  console.error('Seed failed:', err);
  process.exit(1);
});
