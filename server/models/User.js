const pool = require('../config/db');

const User = {
  async findByEmail(email) {
    const [rows] = await pool.query('SELECT * FROM users WHERE email = ?', [email]);
    return rows[0];
  },

  async findById(id) {
    const [rows] = await pool.query('SELECT id, email, created_at FROM users WHERE id = ?', [id]);
    return rows[0];
  }
};

module.exports = User;
