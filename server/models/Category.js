const pool = require('../config/db');
const slugify = require('slugify');

const Category = {
  async getAll() {
    const [rows] = await pool.query('SELECT * FROM categories ORDER BY name ASC');
    return rows;
  },

  async getActive() {
    const [rows] = await pool.query('SELECT * FROM categories WHERE is_hidden = FALSE ORDER BY name ASC');
    return rows;
  },

  async getById(id) {
    const [rows] = await pool.query('SELECT * FROM categories WHERE id = ?', [id]);
    return rows[0];
  },

  async getBySlug(slug) {
    const [rows] = await pool.query('SELECT * FROM categories WHERE slug = ?', [slug]);
    return rows[0];
  },

  async create(name, image) {
    const slug = slugify(name, { lower: true, strict: true });
    const [result] = await pool.query(
      'INSERT INTO categories (name, slug, image) VALUES (?, ?, ?)',
      [name, slug, image || null]
    );
    return { id: result.insertId, name, slug, image };
  },

  async update(id, { name, image, is_hidden }) {
    const slug = name ? slugify(name, { lower: true, strict: true }) : undefined;
    const fields = [];
    const values = [];
    if (name !== undefined) { fields.push('name = ?'); values.push(name); fields.push('slug = ?'); values.push(slug); }
    if (image !== undefined) { fields.push('image = ?'); values.push(image); }
    if (is_hidden !== undefined) { fields.push('is_hidden = ?'); values.push(is_hidden); }
    if (fields.length === 0) return null;
    values.push(id);
    await pool.query(`UPDATE categories SET ${fields.join(', ')} WHERE id = ?`, values);
    return this.getById(id);
  },

  async delete(id) {
    await pool.query('DELETE FROM categories WHERE id = ?', [id]);
    return true;
  },

  async toggleVisibility(id) {
    const category = await this.getById(id);
    if (!category) return null;
    const newHidden = !category.is_hidden;
    await pool.query('UPDATE categories SET is_hidden = ? WHERE id = ?', [newHidden, id]);
    return { ...category, is_hidden: newHidden };
  }
};

module.exports = Category;
