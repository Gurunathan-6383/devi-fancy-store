const ContentPage = require('../models/ContentPage');
const AppError = require('../utils/AppError');

exports.getAll = async (req, res, next) => {
  try {
    const pages = await ContentPage.getAll();
    res.json({ success: true, data: pages });
  } catch (err) { next(err); }
};

exports.getBySlug = async (req, res, next) => {
  try {
    const page = await ContentPage.getBySlug(req.params.slug);
    if (!page) throw new AppError('Page not found', 404);
    res.json({ success: true, data: page });
  } catch (err) { next(err); }
};

exports.getById = async (req, res, next) => {
  try {
    const page = await ContentPage.getById(req.params.id);
    if (!page) throw new AppError('Page not found', 404);
    res.json({ success: true, data: page });
  } catch (err) { next(err); }
};

exports.create = async (req, res, next) => {
  try {
    const { slug, title, content, meta_description, is_active } = req.body;
    if (!slug || !title || !content) throw new AppError('Slug, title, and content are required', 400);
    const page = await ContentPage.create({ slug, title, content, meta_description, is_active });
    res.status(201).json({ success: true, data: page });
  } catch (err) { next(err); }
};

exports.update = async (req, res, next) => {
  try {
    const page = await ContentPage.update(req.params.id, req.body);
    if (!page) throw new AppError('Page not found', 404);
    res.json({ success: true, data: page });
  } catch (err) { next(err); }
};

exports.delete = async (req, res, next) => {
  try {
    await ContentPage.delete(req.params.id);
    res.json({ success: true, message: 'Page deleted' });
  } catch (err) { next(err); }
};
