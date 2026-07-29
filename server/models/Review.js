const pool = require('../config/db');

const Review = {
  async getByProduct(productId) {
    const [rows] = await pool.query(
      `SELECT r.id, r.rating, r.comment, r.created_at, c.name as customer_name
       FROM reviews r
       JOIN customers c ON r.customer_id = c.id
       WHERE r.product_id = ?
       ORDER BY r.created_at DESC`,
      [productId]
    );
    return rows;
  },

  async getStats(productId) {
    const [rows] = await pool.query(
      'SELECT COUNT(*) as count, COALESCE(AVG(rating), 0) as avg_rating FROM reviews WHERE product_id = ?',
      [productId]
    );
    return rows[0];
  },

  async create(customerId, productId, rating, comment) {
    const [result] = await pool.query(
      'INSERT INTO reviews (customer_id, product_id, rating, comment) VALUES (?, ?, ?, ?)',
      [customerId, productId, rating, comment || null]
    );
    return { id: result.insertId, rating, comment };
  },

  async hasReviewed(customerId, productId) {
    const [rows] = await pool.query(
      'SELECT id FROM reviews WHERE customer_id = ? AND product_id = ?',
      [customerId, productId]
    );
    return rows.length > 0;
  }
};

module.exports = Review;
