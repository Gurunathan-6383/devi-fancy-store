const Announcement = require('../models/Announcement');
const AppError = require('../utils/AppError');

exports.getAll = async (req, res, next) => {
  try {
    const announcements = await Announcement.getAll();
    res.json({ success: true, data: announcements });
  } catch (err) { next(err); }
};

exports.getActive = async (req, res, next) => {
  try {
    const announcements = await Announcement.getActive();
    res.json({ success: true, data: announcements });
  } catch (err) { next(err); }
};

exports.getById = async (req, res, next) => {
  try {
    const announcement = await Announcement.getById(req.params.id);
    if (!announcement) throw new AppError('Announcement not found', 404);
    res.json({ success: true, data: announcement });
  } catch (err) { next(err); }
};

exports.create = async (req, res, next) => {
  try {
    const { title, message, type, status, bg_color, text_color, priority, start_date, end_date, redirect_url } = req.body;
    if (!title || !message) throw new AppError('Title and message are required', 400);
    const announcement = await Announcement.create({ title, message, type, status, bg_color, text_color, priority, start_date, end_date, redirect_url });
    res.status(201).json({ success: true, data: announcement });
  } catch (err) { next(err); }
};

exports.update = async (req, res, next) => {
  try {
    const announcement = await Announcement.update(req.params.id, req.body);
    if (!announcement) throw new AppError('Announcement not found', 404);
    res.json({ success: true, data: announcement });
  } catch (err) { next(err); }
};

exports.delete = async (req, res, next) => {
  try {
    await Announcement.delete(req.params.id);
    res.json({ success: true, message: 'Announcement deleted' });
  } catch (err) { next(err); }
};

exports.toggleStatus = async (req, res, next) => {
  try {
    const announcement = await Announcement.toggleStatus(req.params.id);
    if (!announcement) throw new AppError('Announcement not found', 404);
    res.json({ success: true, data: announcement });
  } catch (err) { next(err); }
};
