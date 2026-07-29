const pool = require('../config/db');

const Wishlist = {
  async getByCustomer(customerId) {
    const [rows] = await pool.query(
      `SELECT w.id, w.product_id, p.name, p.slug, p.price, p.offer_price, p.images, p.stock, c.name as category_name
       FROM wishlists w
       JOIN products p ON w.product_id = p.id
       LEFT JOIN categories c ON p.category_id = c.id
       WHERE w.customer_id = ?
       ORDER BY w.created_at DESC`,
      [customerId]
    );
    return rows;
  },

  async add(customerId, productId) {
    const [result] = await pool.query(
      'INSERT IGNORE INTO wishlists (customer_id, product_id) VALUES (?, ?)',
      [customerId, productId]
    );
    return result.affectedRows > 0;
  },

  async remove(customerId, productId) {
    const [result] = await pool.query(
      'DELETE FROM wishlists WHERE customer_id = ? AND product_id = ?',
      [customerId, productId]
    );
    return result.affectedRows > 0;
  },

  async isInWishlist(customerId, productId) {
    const [rows] = await pool.query(
      'SELECT id FROM wishlists WHERE customer_id = ? AND product_id = ?',
      [customerId, productId]
    );
    return rows.length > 0;
  },

  async getIds(customerId) {
    const [rows] = await pool.query('SELECT product_id FROM wishlists WHERE customer_id = ?', [customerId]);
    return rows.map(r => r.product_id);
  }
};

module.exports = Wishlist;
