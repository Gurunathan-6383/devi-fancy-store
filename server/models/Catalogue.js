const pool = require('../config/db');
const slugify = require('slugify');

const Catalogue = {
  async getAll() {
    const [rows] = await pool.query('SELECT * FROM catalogues ORDER BY created_at DESC');
    return rows;
  },

  async getPublished() {
    const [rows] = await pool.query('SELECT * FROM catalogues WHERE is_published = TRUE ORDER BY created_at DESC');
    return rows;
  },

  async getById(id) {
    const [rows] = await pool.query('SELECT * FROM catalogues WHERE id = ?', [id]);
    return rows[0];
  },

  async getBySlug(slug) {
    const [rows] = await pool.query('SELECT * FROM catalogues WHERE slug = ?', [slug]);
    return rows[0];
  },

  async create({ title, description, image }) {
    const slug = slugify(title, { lower: true, strict: true });
    const [result] = await pool.query(
      'INSERT INTO catalogues (title, slug, description, image) VALUES (?, ?, ?, ?)',
      [title, slug, description || null, image || null]
    );
    return this.getById(result.insertId);
  },

  async update(id, data) {
    const fields = [];
    const values = [];
    if (data.title !== undefined) {
      const slug = slugify(data.title, { lower: true, strict: true });
      fields.push('title = ?, slug = ?');
      values.push(data.title, slug);
    }
    if (data.description !== undefined) { fields.push('description = ?'); values.push(data.description); }
    if (data.image !== undefined) { fields.push('image = ?'); values.push(data.image); }
    if (data.is_published !== undefined) { fields.push('is_published = ?'); values.push(data.is_published); }
    if (fields.length === 0) return null;
    values.push(id);
    await pool.query(`UPDATE catalogues SET ${fields.join(', ')} WHERE id = ?`, values);
    return this.getById(id);
  },

  async delete(id) {
    await pool.query('DELETE FROM catalogues WHERE id = ?', [id]);
    return true;
  },

  async togglePublish(id) {
    const catalogue = await this.getById(id);
    if (!catalogue) return null;
    const newPublished = !catalogue.is_published;
    await pool.query('UPDATE catalogues SET is_published = ? WHERE id = ?', [newPublished, id]);
    return { ...catalogue, is_published: newPublished };
  },

  async getProducts(id) {
    const [rows] = await pool.query(`
      SELECT p.*, c.name as category_name
      FROM catalogue_products cp
      JOIN products p ON cp.product_id = p.id
      LEFT JOIN categories c ON p.category_id = c.id
      WHERE cp.catalogue_id = ?
      ORDER BY p.name ASC
    `, [id]);
    return rows.map(r => ({ ...r, images: r.images ? JSON.parse(r.images) : [] }));
  },

  async addProduct(catalogueId, productId) {
    await pool.query(
      'INSERT IGNORE INTO catalogue_products (catalogue_id, product_id) VALUES (?, ?)',
      [catalogueId, productId]
    );
    return true;
  },

  async removeProduct(catalogueId, productId) {
    await pool.query(
      'DELETE FROM catalogue_products WHERE catalogue_id = ? AND product_id = ?',
      [catalogueId, productId]
    );
    return true;
  }
};

module.exports = Catalogue;
