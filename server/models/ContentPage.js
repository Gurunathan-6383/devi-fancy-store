const pool = require('../config/db');

const ContentPage = {
  async getAll() {
    const [rows] = await pool.query('SELECT id, slug, title, meta_description, is_active, created_at, updated_at FROM content_pages ORDER BY id ASC');
    return rows;
  },

  async getBySlug(slug) {
    const [rows] = await pool.query('SELECT * FROM content_pages WHERE slug = ?', [slug]);
    return rows[0];
  },

  async getById(id) {
    const [rows] = await pool.query('SELECT * FROM content_pages WHERE id = ?', [id]);
    return rows[0];
  },

  async create({ slug, title, content, meta_description, is_active }) {
    const [result] = await pool.query(
      'INSERT INTO content_pages (slug, title, content, meta_description, is_active) VALUES (?, ?, ?, ?, ?)',
      [slug, title, content, meta_description || null, is_active !== undefined ? is_active : 1]
    );
    return this.getById(result.insertId);
  },

  async update(id, { title, content, meta_description, is_active }) {
    const fields = [];
    const values = [];
    if (title !== undefined) { fields.push('title = ?'); values.push(title); }
    if (content !== undefined) { fields.push('content = ?'); values.push(content); }
    if (meta_description !== undefined) { fields.push('meta_description = ?'); values.push(meta_description || null); }
    if (is_active !== undefined) { fields.push('is_active = ?'); values.push(is_active ? 1 : 0); }
    if (fields.length === 0) return null;
    values.push(id);
    await pool.query(`UPDATE content_pages SET ${fields.join(', ')} WHERE id = ?`, values);
    return this.getById(id);
  },

  async delete(id) {
    await pool.query('DELETE FROM content_pages WHERE id = ?', [id]);
    return true;
  }
};

module.exports = ContentPage;
