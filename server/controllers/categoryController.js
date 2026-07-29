const Category = require('../models/Category');
const AppError = require('../utils/AppError');

exports.getAll = async (req, res, next) => {
  try {
    const categories = await Category.getAll();
    res.json({ success: true, data: categories });
  } catch (err) { next(err); }
};

exports.getActive = async (req, res, next) => {
  try {
    const categories = await Category.getActive();
    res.json({ success: true, data: categories });
  } catch (err) { next(err); }
};

exports.getById = async (req, res, next) => {
  try {
    const category = await Category.getById(req.params.id);
    if (!category) throw new AppError('Category not found', 404);
    res.json({ success: true, data: category });
  } catch (err) { next(err); }
};

exports.create = async (req, res, next) => {
  try {
    const { name } = req.body;
    if (!name) throw new AppError('Category name is required', 400);
    const image = req.file ? req.file.path : null;
    const category = await Category.create(name, image);
    res.status(201).json({ success: true, data: category });
  } catch (err) { next(err); }
};

exports.update = async (req, res, next) => {
  try {
    const data = {};
    if (req.body.name) data.name = req.body.name;
    if (req.file) data.image = req.file.path;
    if (req.body.is_hidden !== undefined) data.is_hidden = req.body.is_hidden === 'true' || req.body.is_hidden === true;
    const category = await Category.update(req.params.id, data);
    if (!category) throw new AppError('Category not found', 404);
    res.json({ success: true, data: category });
  } catch (err) { next(err); }
};

exports.delete = async (req, res, next) => {
  try {
    await Category.delete(req.params.id);
    res.json({ success: true, message: 'Category deleted' });
  } catch (err) { next(err); }
};

exports.toggleVisibility = async (req, res, next) => {
  try {
    const category = await Category.toggleVisibility(req.params.id);
    if (!category) throw new AppError('Category not found', 404);
    res.json({ success: true, data: category });
  } catch (err) { next(err); }
};
