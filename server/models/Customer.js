const pool = require('../config/db');

const Customer = {
  async findByEmail(email) {
    const [rows] = await pool.query('SELECT * FROM customers WHERE email = ?', [email]);
    return rows[0];
  },

  async findById(id) {
    const [rows] = await pool.query('SELECT id, name, email, phone, created_at FROM customers WHERE id = ?', [id]);
    return rows[0];
  },

  async create({ name, email, phone, password }) {
    const [result] = await pool.query(
      'INSERT INTO customers (name, email, phone, password) VALUES (?, ?, ?, ?)',
      [name, email, phone || null, password]
    );
    return { id: result.insertId, name, email, phone };
  }
};

module.exports = Customer;
