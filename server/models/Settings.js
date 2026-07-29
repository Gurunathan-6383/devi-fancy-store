const pool = require('../config/db');

const Settings = {
  async getAll() {
    const [rows] = await pool.query('SELECT * FROM settings');
    const settings = {};
    rows.forEach(r => { settings[r.setting_key] = r.setting_value; });
    return settings;
  },

  async get(key) {
    const [rows] = await pool.query('SELECT setting_value FROM settings WHERE setting_key = ?', [key]);
    return rows[0] ? rows[0].setting_value : null;
  },

  async set(key, value) {
    await pool.query(
      'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?',
      [key, value, value]
    );
    return true;
  },

  async updateMultiple(settings) {
    for (const [key, value] of Object.entries(settings)) {
      await this.set(key, value);
    }
    return this.getAll();
  }
};

module.exports = Settings;
