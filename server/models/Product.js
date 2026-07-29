const pool = require('../config/db');
const slugify = require('slugify');

const Product = {
  async getAll() {
    const [rows] = await pool.query(`
      SELECT p.*, c.name as category_name, c.slug as category_slug
      FROM products p 
      LEFT JOIN categories c ON p.category_id = c.id 
      ORDER BY p.created_at DESC
    `);
    return rows.map(r => ({ ...r, images: r.images ? JSON.parse(r.images) : [] }));
  },

  async getActive(filters = {}) {
    let sql = `
      SELECT p.*, c.name as category_name, c.slug as category_slug
      FROM products p 
      LEFT JOIN categories c ON p.category_id = c.id 
      WHERE p.status = 'active'
    `;
    const values = [];

    if (filters.category_id) {
      sql += ' AND p.category_id = ?';
      values.push(filters.category_id);
    }
    if (filters.category_slug) {
      sql += ' AND c.slug = ?';
      values.push(filters.category_slug);
    }
    if (filters.search) {
      sql += ' AND (p.name LIKE ? OR p.description LIKE ?)';
      values.push(`%${filters.search}%`, `%${filters.search}%`);
    }
    if (filters.min_price) {
      sql += ' AND COALESCE(p.offer_price, p.price) >= ?';
      values.push(parseFloat(filters.min_price));
    }
    if (filters.max_price) {
      sql += ' AND COALESCE(p.offer_price, p.price) <= ?';
      values.push(parseFloat(filters.max_price));
    }
    if (filters.featured) {
      sql += ' AND p.is_featured = TRUE';
    }

    sql += ' ORDER BY p.created_at DESC';

    if (filters.limit) {
      sql += ' LIMIT ?';
      values.push(parseInt(filters.limit));
    }

    const [rows] = await pool.query(sql, values);
    return rows.map(r => ({ ...r, images: r.images ? JSON.parse(r.images) : [] }));
  },

  async getById(id) {
    const [rows] = await pool.query(`
      SELECT p.*, c.name as category_name, c.slug as category_slug
      FROM products p 
      LEFT JOIN categories c ON p.category_id = c.id 
      WHERE p.id = ?
    `, [id]);
    if (!rows[0]) return null;
    return { ...rows[0], images: rows[0].images ? JSON.parse(rows[0].images) : [] };
  },

  async getBySlug(slug) {
    const [rows] = await pool.query(`
      SELECT p.*, c.name as category_name, c.slug as category_slug
      FROM products p 
      LEFT JOIN categories c ON p.category_id = c.id 
      WHERE p.slug = ?
    `, [slug]);
    if (!rows[0]) return null;
    return { ...rows[0], images: rows[0].images ? JSON.parse(rows[0].images) : [] };
  },

  async create(data) {
    const slug = slugify(data.name, { lower: true, strict: true });
    const images = data.images ? JSON.stringify(data.images) : '[]';
    const [result] = await pool.query(
      `INSERT INTO products (name, slug, category_id, description, specifications, price, offer_price, stock, status, is_featured, images)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [data.name, slug, data.category_id, data.description, data.specifications,
       data.price, data.offer_price || null, data.stock || 0,
       data.status || 'active', data.is_featured || false, images]
    );
    return this.getById(result.insertId);
  },

  async update(id, data) {
    const fields = [];
    const values = [];
    if (data.name !== undefined) {
      const slug = slugify(data.name, { lower: true, strict: true });
      fields.push('name = ?, slug = ?');
      values.push(data.name, slug);
    }
    if (data.category_id !== undefined) { fields.push('category_id = ?'); values.push(data.category_id); }
    if (data.description !== undefined) { fields.push('description = ?'); values.push(data.description); }
    if (data.specifications !== undefined) { fields.push('specifications = ?'); values.push(data.specifications); }
    if (data.price !== undefined) { fields.push('price = ?'); values.push(data.price); }
    if (data.offer_price !== undefined) { fields.push('offer_price = ?'); values.push(data.offer_price); }
    if (data.stock !== undefined) { fields.push('stock = ?'); values.push(data.stock); }
    if (data.status !== undefined) { fields.push('status = ?'); values.push(data.status); }
    if (data.is_featured !== undefined) { fields.push('is_featured = ?'); values.push(data.is_featured); }
    if (data.images !== undefined) { fields.push('images = ?'); values.push(JSON.stringify(data.images)); }
    if (fields.length === 0) return null;
    values.push(id);
    await pool.query(`UPDATE products SET ${fields.join(', ')} WHERE id = ?`, values);
    return this.getById(id);
  },

  async delete(id) {
    await pool.query('DELETE FROM products WHERE id = ?', [id]);
    return true;
  },

  async getFeatured(limit = 8) {
    const [rows] = await pool.query(`
      SELECT p.*, c.name as category_name, c.slug as category_slug
      FROM products p 
      LEFT JOIN categories c ON p.category_id = c.id 
      WHERE p.is_featured = TRUE AND p.status = 'active'
      ORDER BY p.created_at DESC LIMIT ?
    `, [limit]);
    return rows.map(r => ({ ...r, images: r.images ? JSON.parse(r.images) : [] }));
  },

  async search(query, filters = {}) {
    let sql = `
      SELECT p.*, c.name as category_name, c.slug as category_slug
      FROM products p 
      LEFT JOIN categories c ON p.category_id = c.id 
      WHERE p.status = 'active'
    `;
    const values = [];

    if (query) {
      sql += ' AND (p.name LIKE ? OR p.description LIKE ?)';
      values.push(`%${query}%`, `%${query}%`);
    }
    if (filters.category_id) {
      sql += ' AND p.category_id = ?';
      values.push(filters.category_id);
    }
    if (filters.min_price) {
      sql += ' AND COALESCE(p.offer_price, p.price) >= ?';
      values.push(parseFloat(filters.min_price));
    }
    if (filters.max_price) {
      sql += ' AND COALESCE(p.offer_price, p.price) <= ?';
      values.push(parseFloat(filters.max_price));
    }

    const sortMap = {
      'price_low': 'ORDER BY COALESCE(p.offer_price, p.price) ASC',
      'price_high': 'ORDER BY COALESCE(p.offer_price, p.price) DESC',
      'newest': 'ORDER BY p.created_at DESC',
      'name': 'ORDER BY p.name ASC'
    };
    sql += ' ' + (sortMap[filters.sort] || 'ORDER BY p.created_at DESC');

    const [rows] = await pool.query(sql, values);
    return rows.map(r => ({ ...r, images: r.images ? JSON.parse(r.images) : [] }));
  }
};

module.exports = Product;
