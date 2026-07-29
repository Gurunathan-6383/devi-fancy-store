const pool = require('../config/db');

const Announcement = {
  async getAll() {
    const [rows] = await pool.query('SELECT * FROM announcements ORDER BY priority DESC, created_at DESC');
    return rows;
  },

  async getActive() {
    const [rows] = await pool.query(
      `SELECT * FROM announcements
       WHERE status = 'active'
         AND (start_date IS NULL OR start_date <= NOW())
         AND (end_date IS NULL OR end_date >= NOW())
       ORDER BY priority DESC, created_at ASC`
    );
    return rows;
  },

  async getById(id) {
    const [rows] = await pool.query('SELECT * FROM announcements WHERE id = ?', [id]);
    return rows[0];
  },

  async create(data) {
    const { title, message, type, status, bg_color, text_color, priority, start_date, end_date, redirect_url } = data;
    const [result] = await pool.query(
      `INSERT INTO announcements (title, message, type, status, bg_color, text_color, priority, start_date, end_date, redirect_url)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [title, message, type || 'general', status || 'active', bg_color || '#e04a6f', text_color || '#ffffff', priority || 0, start_date || null, end_date || null, redirect_url || null]
    );
    return this.getById(result.insertId);
  },

  async update(id, data) {
    const fields = [];
    const values = [];
    const allowed = ['title', 'message', 'type', 'status', 'bg_color', 'text_color', 'priority', 'start_date', 'end_date', 'redirect_url'];
    for (const key of allowed) {
      if (data[key] !== undefined) {
        fields.push(`${key} = ?`);
        values.push(data[key] === '' ? null : data[key]);
      }
    }
    if (fields.length === 0) return null;
    values.push(id);
    await pool.query(`UPDATE announcements SET ${fields.join(', ')} WHERE id = ?`, values);
    return this.getById(id);
  },

  async delete(id) {
    await pool.query('DELETE FROM announcements WHERE id = ?', [id]);
    return true;
  },

  async toggleStatus(id) {
    const item = await this.getById(id);
    if (!item) return null;
    const newStatus = item.status === 'active' ? 'inactive' : 'active';
    await pool.query('UPDATE announcements SET status = ? WHERE id = ?', [newStatus, id]);
    return { ...item, status: newStatus };
  }
};

module.exports = Announcement;
